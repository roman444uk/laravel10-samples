<?php

namespace App\Enums\Orders\Stage;

use App\Traits\EnumTrait;

enum StageLateralCrossRatioAdjustEnum: string
{
    use EnumTrait;

    case MOVEMENTS_HYPER_CORRECTION = 'movements-hyper-correction';
    case ELASTIC_INTER_MAXILLARY_TRACTION_KNOB_KNOB = 'elastic-inter-maxillary-traction_knob-knob';
    case ELASTIC_INTER_MAXILLARY_TRACTION_KNOB_MI = 'elastic-inter-maxillary-traction_knob-mi';
}
