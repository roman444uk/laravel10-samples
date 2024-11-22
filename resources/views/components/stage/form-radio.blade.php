@php
    /**
     * @var Illuminate\View\ComponentAttributeBag $attributes
     * @var bool $disabled
     */

    $isInline = $isInline ?? false;
@endphp


<div @class(['form-check', 'form-check-inline' => $isInline])>
    <input {{ $attributes->merge(['class' => 'form-check-input', 'type' => 'radio']) }}>
    <label class="form-check-label" for="{{ $attributes->get('id') }}">
        {{ $attributes->get('label') }}
    </label>
</div>
