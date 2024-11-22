<?php

namespace App\Models\Builders;

use Illuminate\Database\Eloquent\Builder;

class OrderBuilder extends Builder
{
    public function joinDoctors(string $type = 'inner'): self
    {
        return $this->join('doctors', 'doctors.id', '=', 'orders.doctor_id', $type);
    }

    public function leftJoinDoctors(): self
    {
        return $this->joinDoctors('left');
    }

    public function joinDoctorsUsers(string $type = 'inner'): self
    {
        return $this->join('users', 'users.id', '=', 'doctors.user_id', $type);
    }

    public function leftJoinDoctorsUsers(): self
    {
        return $this->joinDoctorsUsers('left');
    }

    public function joinStages(string $type = 'inner'): self
    {
        return $this->join('stages', 'stages.order_id', '=', 'orders.id', $type);
    }

    public function leftJoinStages(): self
    {
        return $this->joinStages('left');
    }

    public function doctorId(int $doctorId = null): self
    {
        return $this->when($doctorId, function (Builder $builder, int $doctorId) {
            $builder->where('orders.doctor_id', $doctorId);
        });
    }

    public function search(string $search = null): self
    {
        return $this->when($search, function (Builder $builder, string $search) {
            $builder->where(function(Builder $builder) use ($search) {
                if (is_numeric($search)) {
                    $builder->orWhere('orders.id', $search);
                }

                $builder->orWhereAny(['users.name'], 'ILIKE', '%' . $search . '%');
            });
        });
    }

    public function stageStatusIn(array $statuses = []): self
    {
        return $this->when($statuses, function (Builder $builder, array $statuses) {
            $builder->whereIn('stages.status', $statuses);
        });
    }

    public function clientManagerId(int $clientManagerId = null): self
    {
        return $this->when($clientManagerId, function (Builder $builder, int $clientManagerId) {
            $builder->where('doctors.client_manager_id', $clientManagerId);
        });
    }

    public function clinicalSpecialistId(int $clinicalSpecialistId = null): self
    {
        return $this->when($clinicalSpecialistId, function (Builder $builder, int $clinicalSpecialistId) {
            $builder->where('stages.clinical_specialist_id', $clinicalSpecialistId);
        });
    }

    public function modeler3dId(int $modeler3dId = null): self
    {
        return $this->when($modeler3dId, function (Builder $builder, int $modeler3dId) {
            $builder->where('stages.modeler_3d_id', $modeler3dId);
        });
    }

    public function technicianDigitizerId(int $technicianDigitizerId = null): self
    {
        return $this->when($technicianDigitizerId, function (Builder $builder, int $technicianDigitizerId) {
            $builder->where('stages.technician_digitizer_id', $technicianDigitizerId);
        });
    }

    public function technicianProductionId(int $technicianProductionId = null): self
    {
        return $this->when($technicianProductionId, function (Builder $builder, int $technicianProductionId) {
            $builder->where('stages.technician_production_id', $technicianProductionId);
        });
    }

    public function logisticManagerId(int $logisticManagerId = null): self
    {
        return $this->when($logisticManagerId, function (Builder $builder, int $logisticManagerId) {
            $builder->where('stages.logistic_manager_id', $logisticManagerId);
        });
    }
}
