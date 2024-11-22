@php
    /**
     * @var int $bottom
     * @var string $slot
     */

    $bottom = $bottom ?? false;

    $attributesDefault = ['class' => 'fixed-footer'];
    if ($bottom) {
        $attributesDefault['style'] = 'bottom: ' . $bottom;
    }
@endphp

<div {{ $attributes->except(['bottom'])->merge($attributesDefault) }} @style([
    'bottom: ' . $bottom => $bottom
])>
    {{ $slot }}
</div>
