@php

@endphp

<span {{ $attributes->merge(['class' => 'custom-badge']) }}>
    {{ $slot }}
</span>
