<?php

namespace App\Models\Loaders;

trait StageLoaderTrait
{
    public function loadAllPhotos(): self
    {
        $this->load([
            'photosAdditional',
            'photoFrontal',
            'photoFrontalWithEmma',
            'photoFrontalWithOpenedMouth',
            'photoFrontalWithSmile',
            'photoLateralFromLeft',
            'photoLateralFromRight',
            'photoOcclusiveViewBottom',
            'photoOcclusiveViewTop',
            'photoOpg',
            'photoToProfile',
            'photoScanImpression',
        ]);

        return $this;
    }

    public function loadCheckAccepted(): self
    {
        $this->load('checkAccepted');

        return $this;
    }
}
