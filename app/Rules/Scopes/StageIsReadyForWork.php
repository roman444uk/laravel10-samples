<?php

namespace App\Rules\Scopes;

use App\Classes\Helpers\Db\StageHelper;
use App\Enums\Orders\StageTabEnum;
use App\Models\Stage;

class StageIsReadyForWork
{
    public static function rules(Stage $stage, string $tab = null): array
    {
        if ($tab) {
            return match ($tab) {
                StageTabEnum::PHOTO->value => [
                    'photo_to_profile' => ['required'],
                    'photo_frontal_with_emma' => ['required'],
                    'photo_frontal_with_smile' => ['required'],
                    'photo_frontal_with_opened_mouth' => ['required'],
                    'photo_occlusive_view_bottom' => ['required'],
                    'photo_occlusive_view_top' => ['required'],
                    'photo_lateral_from_right' => ['required'],
                    'photo_lateral_from_left' => ['required'],
                    'photo_frontal' => ['required'],
                ],
                StageTabEnum::SCANS_AND_IMPRESSIONS->value => [
                    'photo_scan_impression' => [StageHelper::isTakeCastsFilled($stage) ? 'sometimes' : 'required'],
                ],
                StageTabEnum::X_RAY_AND_CT->value => [
                    'photo_opg' => ['required'],
//                    'data.xray_and_ct_files_link' => ['required', 'active_url'],
                ],
                default => [],
            };
        }
        return array_merge(
            self::rules($stage, StageTabEnum::PHOTO->value),
            self::rules($stage, StageTabEnum::SCANS_AND_IMPRESSIONS->value),
            self::rules($stage, StageTabEnum::X_RAY_AND_CT->value),
        );
    }

    public static function messages(Stage $stage): array
    {
        return [
            'photo_to_profile.required' => __('stages.validation.photo.required', [
                'label' => __('stages.file_type_enums.to_profile')
            ]),
            'photo_frontal_with_emma.required' => __('stages.validation.photo.required', [
                'label' => __('stages.file_type_enums.frontal_with_emma')
            ]),
            'photo_frontal_with_smile.required' => __('stages.validation.photo.required', [
                'label' => __('stages.file_type_enums.frontal_with_smile')
            ]),
            'photo_frontal_with_opened_mouth.required' => __('stages.validation.photo.required', [
                'label' => __('stages.file_type_enums.frontal_with_opened_mouth')
            ]),
            'photo_occlusive_view_bottom.required' => __('stages.validation.photo.required', [
                'label' => __('stages.file_type_enums.occlusive_view_bottom')
            ]),
            'photo_occlusive_view_top.required' => __('stages.validation.photo.required', [
                'label' => __('stages.file_type_enums.occlusive_view_top')
            ]),
            'photo_lateral_from_right.required' => __('stages.validation.photo.required', [
                'label' => __('stages.file_type_enums.lateral_from_right')
            ]),
            'photo_frontal.required' => __('stages.validation.photo.required', [
                'label' => __('stages.file_type_enums.frontal')
            ]),

            'photo_scan_impression.required' => __('stages.validation.photo.required', [
                'label' => __('stages.file_type_enums.scan_impression')
            ]),

            'photo_opg.required' => __('stages.validation.photo.required', [
                'label' => __('stages.file_type_enums.opg')
            ]),
            'data.xray_and_ct_files_link.required' => __('stages.validation.xray_and_ct_files_link.required'),
            'data.xray_and_ct_files_link.active_url' => __('stages.validation.xray_and_ct_files_link.active_url'),
        ];
    }
}
