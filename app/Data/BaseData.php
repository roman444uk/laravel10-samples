<?php

namespace App\Data;

use App\Support\Str;
use Illuminate\Http\Request;

class BaseData extends \Spatie\LaravelData\Data
{
    protected array $incomeFields = [];

    public static function from(mixed ...$payloads): static
    {
        // Push income fields if Request instance came
        $request = null;
        if ($payloads[count($payloads) - 1] instanceof Request) {
            $request = array_pop($payloads);
        }

        /** @var self $instance */
        $instance = static::factory()->from(...$payloads);

        if ($request) {
            $instance->setIncomeFields($request);
        }

        return $instance;
    }

    public function getIncome(string $field, mixed $defaultValueIfNotIncome = null): mixed
    {
        return $this->isIncome($field) ? $this->$field : $defaultValueIfNotIncome;
    }

    public function isIncome(string $field): bool
    {
        return in_array($field, $this->incomeFields);
    }

    protected function setIncomeFields(Request $request): void
    {
        $this->incomeFields = collect(array_keys($request->validated()))
            ->map(
                fn(string $field) => Str::toCamelCase($field, '_')
            )
            ->toArray();
    }
}
