@php
    use App\Support\Utilities\Pagination;

    /**
     * @var Illuminate\Contracts\Pagination\LengthAwarePaginator $collection
     */
@endphp

<div class="row">
    <div class="col-sm-12 col-md-5">
        <div class="dataTables_info" id="DataTables_Table_0_info">
            {{ Pagination::shownRecords($collection) }}
        </div>
    </div>

    <div class="col-sm-12 col-md-7">
        {!! $collection->links('layouts.partials.pagination') !!}
    </div>
</div>
