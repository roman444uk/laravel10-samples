@php
    /**
     * @var Illuminate\View\ComponentAttributeBag $attributes
     * @var bool $disabled
     */

    $isInline = $isInline ?? false;
@endphp

<div class="form-check">
    <input {{ $attributes->merge(['class' => 'form-check-input', 'type' => 'checkbox']) }}>
    <label class="form-check-label" for="{{ $attributes->get('id') }}">
        {{ $attributes->get('label') }}
    </label>
</div>
