@php
    /**
     * @var Illuminate\View\ComponentAttributeBag $attributes
     */
@endphp

@if($attributes->get('label'))
    <label class="focus-label" for="{{ $attributes->get('id') }}">
        {{ $attributes->get('label') }}
        <span @class(['login-danger', 'd-none' => !$attributes->get('required', false)])>*</span>
    </label>
@endif
