<?php

namespace App\Enums\Orders\Stage;

use App\Traits\EnumTrait;

enum StageReplacementInstallationSchemaEnum: string
{
    use EnumTrait;

    case PLANNING_REMOVAL = 'planning-removal';
    case REMOVAL_ACCORDING_LABORATORY_RECOMMENDATIONS = 'removal-according-laboratory-recommendation';
    case DO_NOT_MOVE_TOOTH = 'do-not-move-tooth';
    case DO_NOT_INSTALL_ATTACHMENTS = 'do-not-install-attachments';
    case FILLING = 'filling';
    case IMPLANT = 'implant';
    case VENEER_CROWN = 'veneer-crown';
}
