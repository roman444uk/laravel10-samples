@php
    use App\Classes\Helpers\TeethHelper;
    use App\Models\Stage;
    use App\Support\Arr;
    use App\Support\Str;

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
     * @var array $schemaSelectedData
     * @var array $schemaSelectedType
     */

    $multiple = $multiple ?? true;
    $schemaSelectedData = $schemaSelectedData ?? [];
    $schemaSelectedType = $schemaSelectedType ?? null;

    $mergedAttributes = $attributes->except([
        'stage', 'props', 'prefix-for-id', 'prefix-for-name', 'schema-group', 'disabled', 'field', 'schema-selected-data',
        'schema-selected-type'
    ])->merge(['class' => 'row teeth-schema']);
@endphp

<div
    {{ $mergedAttributes }}
    id="teeth-schema-{{ $schemaGroup }}"
    data-schema-group="{{ $schemaGroup }}"
    data-schema-field="{{ $attributes->get('field') }}"
    data-schema-multiple="{{ (int)$multiple }}"
    data-schema-prop-selected="{{ $schemaSelectedType }}"
>
    @foreach(TeethHelper::schema() as $side => $teeth)
        <div @class(['col-md-6 col-xl-6 d-flex justify-content-between', 'jaw-side-' . $side])>
            @foreach($teeth as $tooth)
                <span @class([
                        'tooth d-flex', 'jaw-tooth-' . $side . '-' . $tooth,
                        'selected' => Arr::getValue($schemaSelectedData, [$side, $tooth]),
                        'disabled' => empty($schemaSelectedData),
                        'flex-column justify-content-end' => in_array($side, [1, 2]),
                        'flex-column-reverse justify-content-start' => in_array($side, [3, 4])
                    ])
                      @style([
                          'display: inline-block;', 'width: ' . (100 / 9) . '%', 'text-align: center;'
                      ])
                      data-side="{{ $side }}"
                      data-tooth="{{ $tooth }}"
                >
                    <span>
                        <img src="{{ '/assets/img/teeth/' . $side . '-' . $tooth . '.svg' }}" height="55px;">
                    </span>
                    <span>
                        {{ $side . $tooth }}
                    </span>

                    @foreach($props as $prop)
                        @php
                            $propUnderscored = Str::delimiterFromDashToUnderscore($prop);
                            $checked = $stage->fieldsChecked($field.'.'.implode('.', [$propUnderscored,'schema', $side, $tooth]));
                        @endphp

                        <x-stage.form-checkbox-hidden :id="$prefixForId.implode('-', [$prop, 'schema', $side, $tooth])"
                                                      :name="$prefixForName.'['.implode('][', [$propUnderscored, 'schema', $side, $tooth]).']'"
                                                      :value="1"
                                                      :checked="$checked"
                                                      :disabled="$disabled"
                                                      :data-schema-prop="$propUnderscored"
                                                      disabled-static="{{ !$multiple ? 1 : 0}}"
                        ></x-stage.form-checkbox-hidden>
                    @endforeach
                </span>
            @endforeach
        </div>
    @endforeach
</div>
