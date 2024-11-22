<?php

namespace App\Enums\Orders\Stage;

use App\Traits\EnumTrait;

enum StageVirtualElasticChainEnum: string
{
    use EnumTrait;

    case NO = 'no';
    case YES_THREE_THREE = 'yes-three-three';
    case YES_SIX_SIX = 'yes-six-six';
}
