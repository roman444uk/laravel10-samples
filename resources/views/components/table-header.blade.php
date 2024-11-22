@php
    /**
     * @var string $columns
     */
@endphp

@foreach($columns as $columnn)
    <x-table-header-column class="sorting_asc"
                           :sortable="$columnn['sortable'] ?? false"
                           :name="$columnn['name'] ?? null"
                           :rowspan="$columnn['rowspan'] ?? null"
                           :colspan="$columnn['colspan'] ?? null"
    ></x-table-header-column>
@endforeach
