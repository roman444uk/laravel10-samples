@php
    /**
     * @var Illuminate\View\ComponentAttributeBag $attributes
     * @var bool $disabled
     */
@endphp

<div class="remember-me">
    <label class="custom_check mr-2 mb-0 d-inline-flex remember-me" for="{{ $attributes->get('id') }}">
        {{ $attributes->get('label') }}
        <input id="{{ $attributes->get('id') }}" type="checkbox" name="{{ $attributes->get('name') }}" value="1">
        <span class="checkmark"></span>
    </label>
</div>
