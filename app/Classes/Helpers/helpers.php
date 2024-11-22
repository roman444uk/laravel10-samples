<?php

use App\Classes\ServicesResponses\OperationResponse;
use App\Enums\System\SessionEnum;
use App\Enums\Users\UserRoleEnum;
use App\Models\Doctor;
use App\Models\Order;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;

/**
 * System functions.
 */
if (!function_exists('envConfig')) {
    function envConfig($key, $default = null)
    {
        return env($key, $default ?? config('env.' . $key));
    }
}

if (!function_exists('preview_path')) {
    function preview_path($path = ''): string
    {
        return public_path('storage/previews/' . trim($path, '/'));
    }
}

if (!function_exists('urlModTime')) {
    function urlModTime($path = null, $parameters = [], $secure = null): UrlGenerator|string
    {
        return url($path . '?v=' . filemtime(public_path($path)), $parameters, $secure ?? Request::secure());
    }
}

/**
 * System controller and services responses.
 */
if (!function_exists('successJsonResponse')) {
    function successJsonResponse(?string $message = '', array $data = [], int $code = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'errors' => [],
            'data' => $data,
        ], $code);
    }
}

if (!function_exists('errorJsonResponse')) {
    function errorJsonResponse(?string $message = '', array $errors = [], int $code = 403): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
            'data' => [],
        ], $code);
    }
}

if (!function_exists('successOperationResponse')) {
    function successOperationResponse(array $data = []): OperationResponse
    {
        return OperationResponse::success($data);
    }
}

if (!function_exists('errorOperationResponse')) {
    function errorOperationResponse(string $message = null, array $errors = [], array $data = []): OperationResponse
    {
        return OperationResponse::error($message, $errors, $data);
    }
}

if (!function_exists('operationJsonResponse')) {
    function operationJsonResponse(OperationResponse $operationResponse, ?array $withData = [], array $extraData = []): JsonResponse
    {
        if (!$operationResponse->isSuccess()) {
            return errorJsonResponse($operationResponse->getMessage(), $operationResponse->getErrors());
        }

        $data = [];
        collect($withData)->each(function ($key) use (&$data, $operationResponse) {
            $data[$key] = $operationResponse->get($key);

            if (!empty($data[$key]) && $data[$key] instanceof Model) {
                $modelClass = get_class($data[$key]);
                /** @var Spatie\LaravelData\Data $modelDataClass */
                $modelDataClass = 'App\\Data\\' . substr($modelClass, strrpos($modelClass, '\\') + 1) . 'Data';

                if (class_exists($modelDataClass)) {
                    $data[$key] = $modelDataClass::fromModel($data[$key]);
                } else {
                    $data[$key] = $data[$key]->toArray();
                }
            }
        });

        return successJsonResponse(null, array_merge($data, $extraData));
    }
}

/**
 * User model.
 */
if (!function_exists('can')) {
    function can($abilities, $arguments = [], User $user = null): bool
    {
        return getUser($user)->can($abilities, $arguments);
    }
}

if (!function_exists('getUser')) {
    function getUser(User $user = null): ?User
    {
        return $user ?: auth()->user();
    }
}

if (!function_exists('getUserLoggedOriginal')) {
    function getUserLoggedOriginal(): ?User
    {
        $loggedUserId = Session::get(SessionEnum::LOGIN_AS_USER_LOGGED_ID->value);

        return $loggedUserId ? User::firstWhere('id', $loggedUserId) : null;
    }
}

if (!function_exists('isUserLoggedFromAnother')) {
    function isUserLoggedFromAnother(User $user = null): ?bool
    {
        if (!getUser($user)) {
            return null;
        }

        $userOriginal = getUserLoggedOriginal();

        return $userOriginal && $userOriginal->id !== getUser($user)->id;
    }
}

if (!function_exists('getProfile')) {
    function getProfile(User $user = null): ?Profile
    {
        return getUser($user)?->profile;
    }
}

if (!function_exists('getUserId')) {
    function getUserId(): ?int
    {
        return getUser()?->id;
    }
}

if (!function_exists('getClientManagerId')) {
    function getClientManagerId(): ?int
    {
        return isUserClientManager() ? getUserId() : null;
    }
}

if (!function_exists('getClinicalSpecialistId')) {
    function getClinicalSpecialistId(): ?int
    {
        return isUserClinicalSpecialist() ? getUserId() : null;
    }
}

if (!function_exists('getDoctorId')) {
    function getDoctorId(User $user = null): ?int
    {
        return getDoctor($user)?->id;
    }
}

if (!function_exists('getModeler3dId')) {
    function getModeler3dId(User $user = null): ?int
    {
        return isUserModeler3d($user) ? getUserId() : null;
    }
}

if (!function_exists('getTechnicianDigitizerId')) {
    function getTechnicianDigitizerId(User $user = null): ?int
    {
        return isUserTechnicianDigitizer($user) ? getUserId() : null;
    }
}

if (!function_exists('getTechnicianProductionId')) {
    function getTechnicianProductionId(User $user = null): ?int
    {
        return isUserTechnicianProduction($user) ? getUserId() : null;
    }
}

if (!function_exists('getLogisticManagerId')) {
    function getLogisticManagerId(User $user = null): ?int
    {
        return isUserLogisticManager($user) ? getUserId() : null;
    }
}

if (!function_exists('getUserRole')) {
    function getUserRole(User $user = null): ?string
    {
        return getUser($user)?->role;
    }
}

if (!function_exists('isUserRoleIn')) {
    function isUserRoleIn(array $roles, User $user = null): bool
    {
        return in_array(getUserRole($user), $roles);
    }
}

if (!function_exists('isUserAdmin')) {
    function isUserAdmin(User $user = null): bool
    {
        return getUserRole($user) === UserRoleEnum::ADMIN->value;
    }
}

if (!function_exists('isUserClientManager')) {
    function isUserClientManager(User $user = null): bool
    {
        return getUserRole($user) === UserRoleEnum::CLIENT_MANAGER->value;
    }
}

if (!function_exists('isUserClinicalDirector')) {
    function isUserClinicalDirector(User $user = null): bool
    {
        return getUserRole($user) === UserRoleEnum::CLINICAL_DIRECTOR->value;
    }
}

if (!function_exists('isUserClinicalSpecialist')) {
    function isUserClinicalSpecialist(User $user = null): bool
    {
        return getUserRole($user) === UserRoleEnum::CLINICAL_SPECIALIST->value;
    }
}

if (!function_exists('isUserDoctor')) {
    function isUserDoctor(User $user = null): bool
    {
        return getUserRole($user) === UserRoleEnum::DOCTOR->value;
    }
}

if (!function_exists('isUserEmployee')) {
    function isUserEmployee(User $user = null): bool
    {
        return isUserRoleIn([
            UserRoleEnum::TECHNICIAN_DIGITIZER->value,
            UserRoleEnum::CLINICAL_SPECIALIST->value,
            UserRoleEnum::CLIENT_MANAGER->value,
            UserRoleEnum::CLINICAL_DIRECTOR->value,
        ], $user);
    }
}

if (!function_exists('isUserModeler3d')) {
    function isUserModeler3d(User $user = null): bool
    {
        return getUserRole($user) === UserRoleEnum::MODELER_3D->value;
    }
}

if (!function_exists('isUserTechnicianDigitizer')) {
    function isUserTechnicianDigitizer(User $user = null): bool
    {
        return getUserRole($user) === UserRoleEnum::TECHNICIAN_DIGITIZER->value;
    }
}

if (!function_exists('isUserTechnicianProduction')) {
    function isUserTechnicianProduction(User $user = null): bool
    {
        return getUserRole($user) === UserRoleEnum::TECHNICIAN_PRODUCTION->value;
    }
}

if (!function_exists('isUserProductionManager')) {
    function isUserProductionManager(User $user = null): bool
    {
        return getUserRole($user) === UserRoleEnum::PRODUCTION_MANAGER->value;
    }
}

if (!function_exists('isUserLogisticManager')) {
    function isUserLogisticManager(User $user = null): bool
    {
        return getUserRole($user) === UserRoleEnum::LOGISTIC_MANAGER->value;
    }
}

if (!function_exists('getOrderEmployees')) {
    function getOrderEmployees(Order $order = null): Collection
    {
        return collect(array_filter([
            $order->doctor->clientManager,
            $order->stageActual->clinicalSpecialist,
            $order->stageActual->modeler3d,
            $order->stageActual->technicianDigitizer,
            $order->stageActual->technicianProduction,
            $order->stageActual->logisticManager,
        ]));
    }
}

/**
 * Doctor model.
 */
if (!function_exists('getDoctor')) {
    function getDoctor(User $user = null): ?Doctor
    {
        if (isUserDoctor($user)) {
            return getUser($user)->doctor;
        }

        return null;
    }
}
