<?php

namespace App\Data\Filters;

use App\Traits\DataFilterTrait;
use Illuminate\Http\Request;

class UserDataFilter extends \Spatie\LaravelData\Data
{
    use DataFilterTrait;

    public function __construct(
        public ?int    $id,
        public ?string  $email,
        public ?string $name,
        public array|string|null $role,
        public ?int $clientManagerId,
        public ?string $search,
        public ?int    $pageSize,
    )
    {
    }

    public static function fromRequest(Request $request): self
    {
        return self::from($request->validated());
    }

    public function clientManagerId(int $clientManagerId = null): self
    {
        $this->clientManagerId = $clientManagerId;

        return $this;
    }

    public function role(array|string $role): self
    {
        $this->role = $role;

        return $this;
    }
}
