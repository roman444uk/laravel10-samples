@php
    /**
     * @var Illuminate\View\ComponentAttributeBag $attributes
     * @var array $lines
     */
@endphp

<div class="step-line-box" {{ $attributes }}>
    <ul class="step-line-list">
        <x-list.steps-item></x-list.steps-item>
    </ul>
</div>
