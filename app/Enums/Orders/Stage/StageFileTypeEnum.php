<?php

namespace App\Enums\Orders\Stage;

use App\Traits\EnumTrait;

enum StageFileTypeEnum: string
{
    use EnumTrait;

    case ADDITIONAL = 'additional';
    case TO_PROFILE = 'to-profile';
    case FRONTAL_WITH_EMMA = 'frontal-with-emma';
    case FRONTAL_WITH_SMILE = 'frontal-with-smile';
    case FRONTAL_WITH_OPENED_MOUTH = 'frontal-with-opened-mouth';
    case OCCLUSIVE_VIEW_TOP = 'occlusive-view-top';
    case OCCLUSIVE_VIEW_BOTTOM = 'occlusive-view-bottom';
    case LATERAL_FROM_RIGHT = 'lateral-from-right';
    case FRONTAL = 'frontal';
    case LATERAL_FROM_LEFT = 'lateral-from-left';
    case OPG = 'opg';
    case SCAN_IMPRESSION = 'scan-impression';

    public static function casesPhotos(): array
    {
        return [
            self::TO_PROFILE,
            self::FRONTAL_WITH_EMMA,
            self::FRONTAL_WITH_SMILE,
            self::FRONTAL_WITH_OPENED_MOUTH,
            self::OCCLUSIVE_VIEW_TOP,
            self::OCCLUSIVE_VIEW_BOTTOM,
            self::LATERAL_FROM_RIGHT,
            self::FRONTAL,
            self::LATERAL_FROM_LEFT,
        ];
    }

    public static function casesScansAndImpressions(): array
    {
        return [
            self::SCAN_IMPRESSION,
        ];
    }

    public static function casesXRayAndCt(): array
    {
        return [
            self::OPG,
        ];
    }
}
