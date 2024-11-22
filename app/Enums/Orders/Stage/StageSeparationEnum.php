<?php

namespace App\Enums\Orders\Stage;

use App\Traits\EnumTrait;

enum StageSeparationEnum: string
{
    use EnumTrait;

    case AT_LABORATORY_DISCRETION = 'at-laboratory-discretion';
    case FROM_FIRST_STEP = 'from-first-step';
    case IN_ARTIFICIAL_CROWNS_AREA = 'in-artificial-crowns-area';
    case IN_BABY_TEETH_AREA = 'in-baby_teeth-area';
    case IN_FRONTAL_AREA = 'in-frontal-area';
    case IN_MOLARS_AREA = 'in-molars-area';
    case IN_PREMOLARS_AREA = 'in-premolars-area';
    case POSTPONE_UNTIL_LEVELING = 'postpone-until-leveling';
}
