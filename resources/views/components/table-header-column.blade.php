@php
    /**
     * @var string $slot
     * @var string $name
     * @var string $route
     * @var boolean $sortable
     * @var array $queryParams
     * @var array $sortIsDefault
     * @var array $sortDefaultDesc
     */

    $sortable = $sortable ?? false;
    $sortIsDefault = $sortIsDefault ?? false;
    $sortDefaultDesc = $sortDefaultDesc ?? null;
    $sort = $queryParams['sort'] ?? null;
    $desc = $queryParams['desc'] ?? null;
@endphp

<th {{ $attributes->only(['class', 'style'])->merge([
    'class' => implode(' ', [
        $sortable ? 'sorting' : '',
    ]),
    'tabindex' => 0
]) }}>
    {{ $slot }}
</th>
