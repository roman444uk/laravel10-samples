@php
    /**
     * @var Illuminate\View\ComponentAttributeBag $attributes
     * @var bool $disabled
     * @var string $selectedValue
     * @var string $selectedLabel
     */

    $disabled = $disabled ?? false;
    $selectedValue = $selectedValue ?? null;
    $selectedLabel = $selectedLabel ?? null;

    $selectedOption = collect($options)->first(fn(array $option) => $option['value'] === $selectedValue || $option['label'] === $selectedLabel);

    $containerAttributes = $attributes->except(['options', 'selected-value', 'selected-label' , 'disabled'])
        ->merge(['class' => 'dropdown action-label' . ($disabled ? ' disabled' : '')]);
@endphp

<div {{ $containerAttributes }}>
    <span @class(['custom-badge', 'dropdown-toggle' => !$disabled, 'status-' . $selectedOption['color']])
          @if(!$disabled) data-bs-toggle="dropdown" aria-expanded="false" @endif
    >
        {{ $selectedOption['label'] }}
    </span>
    @if(!$disabled)
        <div class="dropdown-menu dropdown-menu-end status-staff"
             data-popper-placement="bottom-end"
             style="position: absolute; inset: 0px 0px auto auto; margin: 0px; transform: translate(0px, 30px);"
        >
            @foreach($options as $option)
                @php
                    $isOptionSelected = $option['value'] === $selectedValue || $option['label'] === $selectedLabel
                @endphp

                <a @class(['dropdown-item', $isOptionSelected ? 'selected status-' . $option['color'] : ''])
                   href="javascript:;"
                   data-value="{{ $option['value'] }}"
                   data-color="{{ $option['color'] }}"
                >
                    {{ $option['label'] }}
                </a>
            @endforeach
        </div>
    @endif
</div>
