@php
    /**
     * @var bool $disabled
     * @var string $faIcon
     * @var string $faIconClass
     * @var string $featherIcon
     * @var string $featherIconClass
     * @var bool $hidden
     * @var bool $loadable
     * @var string $loadingText
     * @var string $svgIcon
     * @var string $svgIconClass
     */

    $faIcon = $faIcon ?? null;
    $faIconClass = $faIconClass ?? null;
    $featherIcon = $featherIcon ?? null;
    $featherIconClass = $featherIconClass ?? null;
    $hidden = $hidden ?? false;
    $ionIcon = $ionIcon ?? null;
    $ionIconClass = $ionIconClass ?? null;
    $loadable = $loadable ?? false;
    $svgIcon = $svgIcon ?? null;
    $svgIconClass = $svgIconClass ?? null;
@endphp

<button {{ $attributes->except(['fa-icon', 'loadable'])->merge(['class' => 'btn', 'data-text' => $slot]) }}
        @disabled($disabled ?? false)
>
    @if($faIcon)
        <i @class(['fa', 'fa-' . $faIcon]) aria-hidden="true"></i>
    @endif

    @if($ionIcon)
        <i @class(['ion-' . $ionIcon, $ionIconClass])></i>
    @endif

    @if($featherIcon)
        <i @class(['feather-' . $featherIcon, 'me-2', $featherIconClass]) aria-hidden="true"></i>
    @endif

    @if($svgIcon)
        <img src="/assets/img/icons/{{ $svgIcon }}.svg">
    @endif

    <span class="text">{{ $slot }}</span>

    @if($loadable)
        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display: none;"></span>
        <span class="loading-text" style="display: none;">{{ $loadingText ?? __('Загрузка...') }}</span>
    @endif
</button>
