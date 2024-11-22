@php
    /**
     * @var \Illuminate\Database\Eloquent\Model $model
     * @var string $editLink
     * @var string $destroyLink
     */
@endphp

<td class="text-end">
    <div class="dropdown dropdown-action">
        <a href="#" class="action-icon dropdown-toggle"
           data-bs-toggle="dropdown" aria-expanded="false"
        >
            <i class="fa fa-ellipsis-v"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-end">
            @if($destroyLink)
                <a class="dropdown-item" href="{{ $editLink }}">
                    <i class="fa-solid fa-pen-to-square m-r-5"></i> {{ __('common.edit') }}
                </a>
            @endif
            @if($destroyLink)
                <a class="dropdown-item order-delete" href="{{ $destroyLink }}">
                    <i class="fa fa-trash-alt m-r-5"></i> {{ __('common.delete') }}
                </a>
            @endif
        </div>
    </div>
</td>
