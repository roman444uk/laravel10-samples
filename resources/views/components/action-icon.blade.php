@php
    /**
     * @var bool $disabled
     * @var string $faIcon
     * @var bool $loadable
     */

    $disabled = $disabled ?? false;
    $faIcon = $faIcon ?? null;
    $loadable = $loadable ?? false;

    $attributesString = $disabled
        ? $attributes->except(['title', 'data-action', 'data-url', 'data-bs-toggle', 'data-bs-target'])
            ->merge(['class' => 'action-icon d-inline-flex'])
        : $attributes->except([])->merge(['class' => 'action-icon d-inline-flex cursor-pointer']);
@endphp

@if($disabled)
    <span {{ $attributesString }}>
@else
    <a {{ $attributesString }}>
@endif

        @if($faIcon)
            <i @class(['fa fa-' . $faIcon])></i>
        @endif

        @if($loadable)
            <i @class(['fa fa-spinner'])></i>
        @endif

@if($disabled)
    </span>
@else
    </a>
@endif
