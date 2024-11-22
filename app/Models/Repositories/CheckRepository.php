<?php

namespace App\Models\Repositories;

use App\Data\CheckData;
use App\Models\Check;

class CheckRepository
{
    public function create(CheckData $checkData): ?Check
    {
        return Check::create([
            'stage_id' => $checkData->stageId,
            'number' => $checkData->number,
            'status' => $checkData->status,
            'date' => $checkData->date,
            'viewer_url' => $checkData->viewerUrl,
            'steps_count' => $checkData->stepsCount,
        ]);
    }

    public function update(Check $check, CheckData $checkData): bool
    {
        $check->fill([
            'status' => $checkData->status ?? $check->status,
            'available_to_doctor' => $checkData->availableToDoctor ?? $check->available_to_doctor,
            'date' => $checkData->date ?? $check->date,
            'viewer_url' => $checkData->viewerUrl ?? $check->viewer_url,
            'steps_count' => $checkData->stepsCount ?? $check->steps_count,
        ]);

        return $check->save();
    }
}
