@php
    use App\Classes\Helpers\TeethHelper;
    use App\Models\Stage;

    /**
     * @var Illuminate\View\ComponentAttributeBag $attributes
     * @var bool $disabled
     * @var string $field
     * @var bool $multiple
     * @var string $prefixForId
     * @var string $prefixForName
     * @var array $props
     * @var Stage $stage
     * @var string $schemaGroup
     * @var array $schemaSelectedType
     */

    $multiple = $multiple ?? true;

    $mergedAttributes = $attributes->except([
        'stage', 'props', 'prefix-for-id', 'prefix-for-name', 'schema-group', 'disabled', 'field', 'schema-selected-type'
    ])->merge(['class' => 'row teeth-schema-details mt-4'])
@endphp

<div
    {{ $mergedAttributes }}
    id="teeth-schema-details-{{ $schemaGroup }}"
    data-schema-group="{{ $schemaGroup }}"
    data-schema-field="{{ $attributes->get('field') }}"
>
    @foreach($props as $prop => $label)
        @php
            $propUnderscored = \App\Support\Str::delimiterFromDashToUnderscore($prop);
            $schema = $stage->fieldsGet(implode('.', [$field, $propUnderscored, 'schema']));
        @endphp

        <div @class(['d-flex flex-row teeth-schema-details-row', 'd-none' => !$multiple && $schemaSelectedType !== $prop])
             data-schema-prop="{{ $propUnderscored }}"
        >
            <div>
                {{ $label }}:
            </div>
            <div class="details">&nbsp{{
                    collect($schema ?? [])->map(function ($data, $side) {
                        return collect(array_filter($data))->map(function ($value, $tooth) use($side) {
                            return implode('.', [$side, $tooth]);
                        })->implode(', ');
                    })->filter()->implode(', ')
                }}
            </div>
        </div>
    @endforeach
</div>
