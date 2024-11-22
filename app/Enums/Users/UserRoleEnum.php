<?php

namespace App\Enums\Users;

use App\Traits\EnumTrait;

enum UserRoleEnum: string
{
    use EnumTrait;

    case ADMIN = 'admin';
    case CLIENT_MANAGER = 'client-manager';
    case CLINICAL_DIRECTOR = 'clinical-director';
    case CLINICAL_SPECIALIST = 'clinical-specialist';
    case DOCTOR = 'doctor';
//    case LOGISTICS = 'logistics';
    case LOGISTIC_MANAGER = 'logistic-manager';
    case MAINTENANCE_MANAGER = 'maintenance-manager';
    case MODELER_3D = 'modeler-3d';
//    case PRODUCTION_DEPARTMENT = 'production-department';
    case PRODUCTION_MANAGER = 'production-manager';
    case QUALITY_MANAGER = 'quality-employee';
    case TECHNICIAN = 'technician';
    case TECHNICIAN_DIGITIZER = 'technician-digitizer';
    case TECHNICIAN_PRODUCTION = 'technician-production';

    public static function casesAvailable(): array
    {
        return match (getUserRole()) {
            UserRoleEnum::ADMIN->value => UserRoleEnum::cases(),
            UserRoleEnum::CLINICAL_DIRECTOR->value,
            UserRoleEnum::CLIENT_MANAGER->value => [
                UserRoleEnum::DOCTOR
            ],
            default => [],
        };
    }
}
