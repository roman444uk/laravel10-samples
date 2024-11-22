<?php

namespace App\Classes\Helpers\Db;

use App\Enums\Orders\StageStatusEnum;
use App\Enums\Orders\StageTabEnum;
use App\Enums\Orders\StageTypeEnum;
use App\Enums\Users\UserRoleEnum;
use App\Models\Stage;
use App\Models\User;
use App\Rules\Scopes\StageIsReadyForWork;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;

class StageHelper
{
    /**
     * Whether status can be changes directly.
     */
    public static function canStatusBeChangedDirectly(string $status): bool
    {
        return in_array($status, self::isStatusesDirectlyChangeable());
    }

    /**
     * Whether user can change status from certain status.
     */
    public static function canStatusBeChangedByUser(string $status, User $user): bool
    {
        return in_array($status, self::getStatusesWithAction(getUserRole($user)));
    }

    /**
     * Statuses collection that stage's status can be changed to. Each role has different rights for status changing.
     */
    public static function getAvailableStatusesForUpdating(Stage $stage, User $user): Collection
    {
        $cases = StageStatusEnum::getValues('casesChronological');
        $currentKey = array_search($stage->status, $cases);

        if (isUserAdmin($user)) {
            return collect($cases);
        }

        $stepBackDefault = 1;

        $stepBack = match ($user->role) {
            UserRoleEnum::DOCTOR->value => match ($stage->status) {
                StageStatusEnum::PRODUCTION_OPTIONS->value => 0,
                default => $stepBackDefault,
            },
            UserRoleEnum::CLINICAL_DIRECTOR->value => null,
            UserRoleEnum::PRODUCTION_MANAGER->value => match ($stage->status) {
                StageStatusEnum::PRODUCTION_PREPARATION->value,
                StageStatusEnum::PRODUCTION_CONTROL->value => 0,
                default => $stepBackDefault,
            },
            UserRoleEnum::TECHNICIAN_PRODUCTION->value => match ($stage->status) {
                StageStatusEnum::PRODUCTION_RELEASE->value,
                StageStatusEnum::PRODUCTION_PACKAGING->value => 0,
                default => $stepBackDefault,
            },
            UserRoleEnum::LOGISTIC_MANAGER->value => match ($stage->status) {
                StageStatusEnum::DELIVERY_PREPARATION->value,
                StageStatusEnum::DELIVERY->value => 0,
                default => $stepBackDefault,
            },
            default => $stepBackDefault,
        };

        $stepForward = match ($user->role) {
            UserRoleEnum::CLINICAL_DIRECTOR->value => match ($stage->status) {
                StageStatusEnum::PAYMENT_BILL->value => 4,
                StageStatusEnum::PAYMENT_AWAITING->value => 2,
                default => 1
            },
            UserRoleEnum::MODELER_3D->value => 0,
            default => 1,
        };

        $keyFrom = $stepBack === null || $currentKey === 0 ? 0 : $currentKey - $stepBack;
        $keyTo = $currentKey + $stepForward;

        return collect(array_splice(
            $cases,
            $keyFrom,
            $keyTo - $keyFrom + 1
        ));
    }

    /**
     * Return stage's statuses list with which orders are available for users with a particular role.
     */
    public static function getStatusesOfAvailableOrders(string $role): ?Collection
    {
        return collect(match ($role) {
            UserRoleEnum::PRODUCTION_MANAGER->value => [
                StageStatusEnum::PRODUCTION_PREPARATION->value,
                StageStatusEnum::PRODUCTION_RELEASE->value,
                StageStatusEnum::PRODUCTION_PACKAGING->value,
                StageStatusEnum::PRODUCTION_CONTROL->value,
                StageStatusEnum::DELIVERY_PREPARATION->value,
                StageStatusEnum::DELIVERY->value,
                StageStatusEnum::DELIVERED->value,
                StageStatusEnum::TREATMENT->value,
            ],
            default => []
        });
    }

    /**
     * List of statuses that user can change stage status from.
     */
    public static function getStatusesWithAction(string $role): array
    {
        return match ($role) {
//            UserRoleEnum::DOCTOR->value => [
//                StageStatusEnum::PRODUCTION_OPTIONS->value,
//            ],
            UserRoleEnum::CLINICAL_SPECIALIST->value => [
                StageStatusEnum::VERIFICATION->value,
                StageStatusEnum::CHECK_VERIFICATION_BY_CLINICAL_SPECIALIST->value,
            ],
            UserRoleEnum::TECHNICIAN_DIGITIZER->value => [
                StageStatusEnum::PREPARATION->value,
            ],
            UserRoleEnum::MODELER_3D->value => [
                StageStatusEnum::MODELING->value,
            ],
            UserRoleEnum::CLINICAL_DIRECTOR->value => StageStatusEnum::getValues('casesChronological', [], [StageStatusEnum::DRAFT->value])/*[
                StageStatusEnum::CHECK_VERIFICATION_BY_CLINICAL_DIRECTOR->value,
                StageStatusEnum::PAYMENT_BILL_AFTER_REJECTION->value,   // temporarily, because will be st automatically in the future
                StageStatusEnum::PAYMENT_BILL->value,                   // temporarily, because will be st automatically in the future
                StageStatusEnum::PAYMENT_AWAITING->value,               // temporarily, because will be st automatically in the future
            ]*/,
            UserRoleEnum::PRODUCTION_MANAGER->value => [
                StageStatusEnum::PRODUCTION_PREPARATION->value,
                StageStatusEnum::PRODUCTION_CONTROL->value,
            ],
            UserRoleEnum::TECHNICIAN_PRODUCTION->value => [
                StageStatusEnum::PRODUCTION_RELEASE->value,
                StageStatusEnum::PRODUCTION_PACKAGING->value,

            ],
            UserRoleEnum::LOGISTIC_MANAGER->value => [
                StageStatusEnum::DELIVERY_PREPARATION->value,
                StageStatusEnum::DELIVERY->value,
            ],
            default => []
        };
    }

    /**
     * Whether model has reached particular status from chronological perspective.
     */
    public static function hasReachedStatus(Stage $stage, StageStatusEnum|string $status): bool
    {
        $statusesChronological = StageStatusEnum::getValues('casesChronological');

        return array_search($stage->status, $statusesChronological) >= array_search($status, $statusesChronological);
    }

    /**
     * Unique title.
     */
    public static function getTitle(Stage $stage): string
    {
        return $stage->type === StageTypeEnum::TREATMENT->value
            ? __('stages.main_treatment') : __('stages.correction_with_number', ['number' => $stage->number]);
    }

    /**
     * Whether stage available for edition.
     */
    public static function isAvailableForEdition(Stage $stage): bool
    {
        return $stage->status === StageStatusEnum::DRAFT->value;
    }

    public static function isTakeCastsFilled(Stage $stage): bool
    {
        return $stage->take_casts_address_id && $stage->take_casts_date && $stage->take_casts_time;
    }

    /**
     * Whether status can be changed directly. Only if not related to some user action.
     */
    public static function isStatusesDirectlyChangeable(): array
    {
        return [
            StageStatusEnum::VERIFICATION->value,
            StageStatusEnum::PREPARATION->value,
            StageStatusEnum::MODELING->value,
            StageStatusEnum::PAYMENT_BILL->value,       // temporarily, because will be st automatically in the future
            StageStatusEnum::PAYMENT_AWAITING->value,   // temporarily, because will be st automatically in the future
//            StageStatusEnum::PRODUCTION_OPTIONS->value,
            StageStatusEnum::PRODUCTION_PREPARATION->value,
            StageStatusEnum::PRODUCTION_RELEASE->value,
            StageStatusEnum::PRODUCTION_PACKAGING->value,
            StageStatusEnum::PRODUCTION_CONTROL->value,
            StageStatusEnum::DELIVERY_PREPARATION->value,
            StageStatusEnum::DELIVERY->value,
        ];
    }

    public static function getSelectOptions(Collection $collection, string $emptyOption = null): Collection
    {
        if ($emptyOption !== null) {
            $collection->prepend($emptyOption, '');
        }

        return collect($collection->toArray());
    }

    /**
     * Whether stage can be moved from drat to next statuses. Also returns errors stat about required fields.
     */
    public static function isReadyForWork(Stage $stage, bool $returnErrors = true): Collection|bool
    {
        $stage->loadAllPhotos();

        $validator = Validator::make(
            array_merge($stage->toArray(), $stage->relationsToArray()),
            StageIsReadyForWork::rules($stage),
            StageIsReadyForWork::messages($stage)
        )->stopOnFirstFailure(false);

        if ($returnErrors === false) {
            return !$validator->fails();
        }

        $ready = true;
        $tabs = [];
        collect(StageTabEnum::cases())->each(function (StageTabEnum $enum) use (&$ready, &$tabs, $validator, $stage) {
            $intersect = array_intersect(array_keys(StageIsReadyForWork::rules($stage, $enum->value)), array_keys($validator->errors()->messages()));

            $ready = count($intersect) === 0 ? $ready : false;
            $tabs[$enum->value] = count($intersect) === 0;
        });

        return collect([
            'ready' => $ready,
            'errors' => $validator->errors()->messages(),
            'tabs' => $tabs,
        ]);
    }

//    public static function getStatusColor(string $status = null): array|string
//    {
//        $colors = [
//            StageStatusEnum::DRAFT->value => 'green',
//            StageStatusEnum::VERIFICATION->value => 'green',
//            StageStatusEnum::PREPARATION->value => 'green',
//            StageStatusEnum::MODELING->value => 'green',
//            StageStatusEnum::CHECK_VERIFICATION_BY_CLINICAL_SPECIALIST->value => 'green',
//            StageStatusEnum::CHECK_VERIFICATION_BY_CLINICAL_DIRECTOR->value => 'green',
//            StageStatusEnum::CHECK_VERIFICATION_BY_DOCTOR->value => 'green',
//            StageStatusEnum::PAYMENT_BILL_AFTER_REJECTION->value => 'green',
//            StageStatusEnum::PAYMENT_BILL->value => 'green',
//            StageStatusEnum::PAYMENT_AWAITING->value => 'green',
//            StageStatusEnum::PRODUCTION_OPTIONS->value => 'green',
//            StageStatusEnum::PRODUCTION_PREPARATION->value => 'green',
//            StageStatusEnum::PRODUCTION_RELEASE->value => 'green',
//            StageStatusEnum::PRODUCTION_PACKAGING->value => 'green',
//            StageStatusEnum::PRODUCTION_CONTROL->value => 'green',
//            StageStatusEnum::DELIVERY_PREPARATION->value => 'green',
//            StageStatusEnum::DELIVERY->value => 'green',
//            StageStatusEnum::DELIVERED->value => 'green',
//            StageStatusEnum::TREATMENT->value => 'green',
//            StageStatusEnum::COMPLETED->value => 'green',
//        ];
//
//        return $status ? $colors[$status] : $colors;
//    }


    public static function isTakeCastsAvailableForEdition($stage): bool
    {
        return self::isAvailableForEdition($stage) && isUserDoctor() && !$stage->take_casts_address_id;
    }
}
