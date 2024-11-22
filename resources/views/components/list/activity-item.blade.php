@php
    /**
     * @var Illuminate\View\ComponentAttributeBag $attributes
     * @var Illuminate\Support\Carbon|string $date
     * @var Illuminate\Support\Carbon|string|null $time
     * @var string $slot
     */
@endphp

<li {{ $attributes }}>
{{--    <div class="activity-user">--}}
{{--        <a href="profile.html" data-bs-toggle="tooltip" class="avatar" aria-label="Lesley Grauer" data-bs-original-title="Lesley Grauer">--}}
{{--            <img alt="Lesley Grauer" src="/assets/img/user-02.jpg" class="img-fluid rounded-circle">--}}
{{--        </a>--}}
{{--    </div>--}}
    <div class="activity-content timeline-group-blk">
        <div class="timeline-group flex-shrink-0">
            <h4>{{ $date }}</h4>
            @if(isset($time))
                <span class="time">{{ $time }}</span>
            @endif
        </div>
        <div class="comman-activitys flex-grow-1">
            {{ $slot }}
        </div>
    </div>
</li>
