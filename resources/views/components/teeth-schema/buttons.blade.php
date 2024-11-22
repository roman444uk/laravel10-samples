@php
    use App\Models\Stage;use App\Support\Str;

    /**
     * @var bool $disabled
     * @var array|App\Traits\EnumTrait[] $enumCases
     * @var bool $multiple
     * @var string $prefixForId
     * @var string $prefixForName
     * @var string $schemaGroup
     * @var string $schemaField
     * @var Stage $stage
     * @var callable $uniqueFunc
     */

    $disabled = $disabled ?? false;
    $multiple = $multiple ?? true;
@endphp

<div class="teeth-schema-bnt-group">
    @foreach($enumCases as $index => $enum)
        @php
            $prop = Str::delimiterFromDashToUnderscore($enum->value);
            $selected = $multiple ? false : $stage->fieldsEqual($schemaField.'.type', $prop);
            $uniqueArray = isset($uniqueFunc) && is_callable($uniqueFunc) ? call_user_func($uniqueFunc, ) : ['type'];
        @endphp

        <button type="button"
                @class([
                    'teeth-schema-bnt btn btn-primary me-2 mb-3',
                    'cancel-form' => !$selected,
                ])
                data-schema-group="{{ $schemaGroup }}"
                data-schema-field="{{ $schemaField }}"
                data-schema-prop="{{ $prop }}"
                data-schema-multiple="{{ (int)$multiple }}"
                @disabled($disabled)
        >
            {{ __('stages.' . $prop) }}
        </button>

        @if(!$multiple)
            <x-stage.form-checkbox-hidden :id="$prefixForId.implode('-', $uniqueArray)"
                                          :name="$prefixForName.'['.implode('][', $uniqueArray).']'"
                                          :value="$prop"
                                          :checked="$selected"
                                          :disabled="$disabled"
                                          :data-schema-prop="$prop"
                                          disabled-static="{{ !$multiple ? 1 : 0}}"
            ></x-stage.form-checkbox-hidden>
        @endif
    @endforeach
</div>
