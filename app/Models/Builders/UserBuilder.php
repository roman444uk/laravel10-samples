<?php

namespace App\Models\Builders;

use App\Classes\Helpers\PhoneHelper;
use App\Enums\Users\UserRoleEnum;
use Illuminate\Database\Eloquent\Builder;

class UserBuilder extends Builder
{
    public function joinDoctors(string $type = 'inner'): self
    {
        return $this->join('doctors', 'doctors.user_id', '=', 'users.id', $type);
    }

    public function leftJoinDoctors(): self
    {
        return $this->joinDoctors('left');
    }

    public function isClientManager(): self
    {
        return $this->where('role', UserRoleEnum::CLIENT_MANAGER->value);
    }

    public function isClinicalDirector(): self
    {
        return $this->where('role', UserRoleEnum::CLINICAL_DIRECTOR->value);
    }

    public function isClinicalSpecialist(): self
    {
        return $this->where('role', UserRoleEnum::CLINICAL_SPECIALIST->value);
    }

    public function isDoctor(): self
    {
        return $this->where('role', UserRoleEnum::DOCTOR->value);
    }

    public function isLogisticManager(): self
    {
        return $this->where('role', UserRoleEnum::LOGISTIC_MANAGER->value);
    }

    public function isModeler3d(): self
    {
        return $this->where('role', UserRoleEnum::MODELER_3D->value);
    }

    public function isProductionManager(): self
    {
        return $this->where('role', UserRoleEnum::PRODUCTION_MANAGER->value);
    }

    public function isTechnicianDigitizer(): self
    {
        return $this->where('role', UserRoleEnum::TECHNICIAN_DIGITIZER->value);
    }

    public function isTechnicianProduction(): self
    {
        return $this->where('role', UserRoleEnum::TECHNICIAN_PRODUCTION->value);
    }

    public function role(string|array $role = null): self
    {
        return $this->when($role, function (Builder $builder, string|array $role) {
            $builder->whereIn('role', is_array($role) ? $role : [$role]);
        });
    }

    public function search(string $search = null): self
    {
        return $this->when($search, function (Builder $builder, string $search) {
            $columns = ['email', 'name'];

            $phone = PhoneHelper::toRawFormat($search);
            if (strlen($phone) > 3) {
                $columns[] = 'phone';
            }

            $builder->whereAny($columns, 'ILIKE', '%' . $search . '%');
        });
    }

    public function clientManagerId(int $clientManagerId = null): self
    {
        return $this->when($clientManagerId, function (Builder $builder, int $clientManagerId) {
            $builder->where('doctors.client_manager_id', $clientManagerId);
        });
    }
}
