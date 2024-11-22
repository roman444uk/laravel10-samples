@php
    /**
     * @var bool $value
     */
@endphp

<span @class(['custom-badge', 'status-' . ($value ? 'green' : 'red')])>
    {{ $value ? __('buttons.yes') : __('buttons.no') }}
</span>
