<?php

namespace App\Enums\Orders\Stage;

use App\Traits\EnumTrait;

enum StageCorrectionReasonEnum: string
{
    use EnumTrait;

    case FINAL_DETAILING = 'final-detailing';
    case CHANGED_GOALS = 'changed-goals';
    case NEW_RESTORATION = 'new-restoration';
    case OTHER_PROBLEMS = 'other-problems';
}
