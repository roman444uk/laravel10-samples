<?php

namespace App\Data;

use App\Classes\Helpers\Db\CheckHelper;
use App\Models\Check;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class CheckData extends \Spatie\LaravelData\Data
{
    public function __construct(
        public ?int                 $id,
        public ?int                 $stageId,
        public ?int                 $orderId,
        public ?int                 $number,
        public ?string              $numberLabel,
        public ?string              $status,
        public ?bool                $availableToDoctor,
        public ?Carbon              $date,
        public ?string              $viewerUrl,
        public ?int                 $stepsCount,
        public ?Carbon              $createdAt,
        public ?Carbon              $updatedAt,
        public FileData|string|null $fileSetupBottom,
        public FileData|string|null $fileSetupTop,
        public ?string              $fileSetupBottomName,
        public ?string              $fileSetupTopName,
        public ?bool                $canUpdate,
        public ?bool                $canDelete,
        public ?bool                $canDeleteFilesSetup,
    )
    {
    }

    public static function fromModel(Check $check): self
    {
        return new self(
            $check->id,
            $check->stage_id,
            $check->whenLoaded('stage', fn(Check $check) => $check->stage->order_id),
            $check->number,
            CheckHelper::number($check),
            $check->status,
            $check->available_to_doctor,
            $check->date,
            $check->viewer_url,
            $check->steps_count,
            $check->created_at,
            $check->updated_at,
            $check->whenLoaded('fileSetupBottom', fn(Check $check) => FileData::fromModel($check->fileSetupBottom)),
            $check->whenLoaded('fileSetupTop', fn(Check $check) => FileData::fromModel($check->fileSetupTop)),
            null,
            null,
            can('update', $check),
            can('delete', $check),
            can('deleteFilesSetup', $check),
        );
    }

    public static function fromRequest(Request $request): self
    {
        return self::from([
            'status' => $request->validated('status'),
            'availableToDoctor' => $request->validated('available_to_doctor'),
            'stepsCount' => $request->validated('steps_count'),
            'viewerUrl' => $request->validated('viewer_url'),
            'fileSetupBottom' => $request->file('setup_bottom') ?? $request->validated('setup_bottom'),
            'fileSetupTop' => $request->file('setup_top') ?? $request->validated('setup_top'),
            'fileSetupBottomName' => null,
            'fileSetupTopName' => null,
        ]);
    }
}
