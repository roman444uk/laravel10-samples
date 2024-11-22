<?php

namespace App\Enums\Orders;

use App\Traits\EnumTrait;

enum CheckStatusEnum: string
{
    use EnumTrait;

    case ACCEPTED = 'accepted';
    case NEW = 'new';
    case REJECTED_BY_CLINICAL_DIRECTOR = 'rejected-by-clinical-director';
    case REJECTED_BY_CLINICAL_SPECIALIST = 'rejected-by-clinical-specialist';
    case REJECTED_BY_DOCTOR = 'rejected-by-doctor';
    case VERIFICATION_BY_CLINICAL_DIRECTOR= 'verification-by-clinical-director';
    case VERIFICATION_BY_CLINICAL_SPECIALIST = 'verification-by-clinical-specialist';
    case VERIFICATION_BY_DOCTOR = 'verification-by-doctor';
}
