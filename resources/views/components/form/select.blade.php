@php
    /**
     * @var Illuminate\View\ComponentAttributeBag $attributes
     * @var bool $disabled
     * @var string $selectedValue
     * @var string $selectedLabel
     */

    $selectedValue = $selectedValue ?? null;
    $selectedLabel = $selectedLabel ?? null;
@endphp

<x-form.label :id="$attributes->get('id')"
              :label="$attributes->get('label')"
              :required="$attributes->get('required')"
></x-form.label>

<select {{ $attributes->except(['options', 'label', 'empty'])->merge(['class' => 'form-control']) }}>
    @foreach($options as $value => $label)
        <option value="{{ $value }}" @selected($value == $selectedValue || $label == $selectedLabel)>
            {{ $label }}
        </option>
    @endforeach
</select>
