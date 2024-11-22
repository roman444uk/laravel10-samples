@php
    /**
     * @var string $title
     * @var string $slot
     * @var string $buttons
     */
@endphp

<div {{ $attributes->merge(['class' => 'modal fade']) }} tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    {{ $title ?? '' }}
                </h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{ $slot }}
            </div>
            <div class="modal-footer">
                {{ $buttons }}
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('buttons.cancel') }}</button>
            </div>
        </div>
    </div>
</div>
