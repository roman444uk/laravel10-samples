<?php

namespace App\Models\Loaders;

trait OrderLoaderTrait
{
    public function loadStages(): self
    {
        $this->load(['stages']);

        return $this;
    }
}
