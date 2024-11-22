<?php

namespace App\Data\Filters;

use App\Traits\DataFilterTrait;
use Illuminate\Http\Request;

class NotificationDataFilter extends \Spatie\LaravelData\Data
{
    use DataFilterTrait;

    public function __construct(
        public ?int              $id,
        public string|array|null $type,
        public string|array|null $notifiableType,
        public ?int              $notifiableId,
        public ?array            $data,
        public ?int              $pageSize,
        public ?string           $sort,
        public ?string           $desc,
    )
    {
    }

    public static function fromRequest(Request $request): self
    {
        return self::from($request->validated());
    }

    public function type(string|array $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function notifiableType(string|array $notifiableType): self
    {
        $this->notifiableType = $notifiableType;

        return $this;
    }

    public function notifiableId(string|array $notifiableId): self
    {
        $this->notifiableId = $notifiableId;

        return $this;
    }
}
