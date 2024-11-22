@php
    /**
     * @var Illuminate\View\ComponentAttributeBag $attributes
     * @var bool $disabled
     */
@endphp

<input {{ $attributes->merge(['type' => 'checkbox', 'style' => 'display: none;']) }}>
