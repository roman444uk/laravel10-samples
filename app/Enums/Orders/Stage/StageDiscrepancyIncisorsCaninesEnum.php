<?php

namespace App\Enums\Orders\Stage;

use App\Traits\EnumTrait;

enum StageDiscrepancyIncisorsCaninesEnum: string
{
    use EnumTrait;

    case LEAVE_SPACES_DISTAL_TO_LATERAL_INCISORS = 'leave-spaces-distal-to-lateral-incisors';
    case LEAVE_SPACES_DISTAL_TO_MESIAL_LATERAL_INCISORS = 'leave-spaces-distal-to-mesial-lateral-incisors';
    case ANTAGONISTS_SEPARATION = 'antagonists_separation';
    case OPEN_TREMS_WHILE_SEPARATION = 'open_trems_while_separation';
}
