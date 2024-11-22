<?php

namespace App\Rules\Orders;

use App\Enums\Orders\StageStatusEnum;
use App\Models\Stage;
use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

class StageStatusRule implements DataAwareRule, ValidationRule
{
    /**
     * All of the data under validation.
     *
     * @var array<string, mixed>
     */
    protected $data = [];

    /**
     * Set the data under validation.
     *
     * @param  array<string, mixed>  $data
     */
    public function setData(array $data): static
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        /** @var Stage $stage */
        $stage = request()->route()->parameter('stage');

        if ($this->data['status'] === StageStatusEnum::PRODUCTION_PREPARATION->value && $stage->checkAccepted->productions->count() === 0) {
            $fail(__('stages.validation.status.create_production'));
        }

        if ($this->data['status'] === StageStatusEnum::PRODUCTION_RELEASE->value && (
                $stage->checkAccepted->productions->count() === 0 || !$stage->checkAccepted->latestProduction->production_term
            )) {
            $fail(__('productions.validation.production_term.required'));
        }
    }
}
