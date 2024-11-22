@php
    /**
     * @var Illuminate\View\ComponentAttributeBag $attributes
     * @var string $slot
     */
@endphp

<li {{ $attributes }}>
    <div class="step-line-user">
        <div class="before-circle"></div>
    </div>
    <div class="step-line-content">
        <div class="timeline-content">
            {{ $slot }}
        </div>
    </div>
</li>
