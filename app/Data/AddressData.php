<?php

namespace App\Data;

use App\Data\Casts\ArrayFromJson;
use Illuminate\Http\Request;
use Spatie\LaravelData\Attributes\WithCast;

class AddressData extends \Spatie\LaravelData\Data
{
    public function __construct(
        public ?int    $id,
        public ?string $address,
        #[WithCast(ArrayFromJson::class)]
        public ?array  $data,
    )
    {
    }

    public static function fromRequest(Request $request): self
    {
        return self::from([
            'id' => $request->validated('id'),
            'address' => trim($request->validated('address')),
            'data' => $request->validated('data'),
        ]);
    }
}
