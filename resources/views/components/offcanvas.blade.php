@php
    /**
     * @var string $class
     * @var string $header
     * @var string $id
     * @var bool $resizable
     * @var string $slot
     */

    $resizable = $resizable ?? false;
@endphp

<div {{ $attributes->merge(['class' => 'offcanvas']) }}
     tabindex="-1"
     aria-labelledby="offcanvasRightLabel"
     aria-modal="true"
     role="dialog"
>
    @if($resizable)
        <div class="offcanvas-resize-wrapper">
            <div class="offcanvas-resizer"></div>
            <div class="offcanvas-resize-stroke"></div>
        </div>
    @endif

    <div class="offcanvas-header">
        @if($header)
            <h4 class="font-bold" id="offcanvasRightLabel">
                {{ $header }}
            </h4>
        @endif

        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>

    <div class="offcanvas-body">
        {{ $slot }}
    </div>
</div>
