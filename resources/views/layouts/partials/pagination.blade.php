@php

    @endphp

@if ($paginator->hasPages())
    <div class="dataTables_paginate paging_simple_numbers"
         id="DataTables_Table_0_paginate"
    >
        <ul class="pagination">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="paginate_button page-item previous disabled" id="DataTables_Table_0_previous">
                    <span class="page-link">
                        {!! __('pagination.previous') !!}
                    </span>
                </li>
            @else
                <li class="paginate_button page-item previous disabled" id="DataTables_Table_0_previous">
                    <a href="#" class="page-link" aria-controls="DataTables_Table_0" data-dt-idx="0" tabindex="0">
                        {!! __('pagination.previous') !!}
                    </a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="paginate_button page-item disabled">
                        <span class="page-link">
                            {{ $element }}
                        </span>
                    </li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="paginate_button page-item disabled current" id="DataTables_Table_0_previous">
                                <span class="page-link">
                                    {{ $page }}
                                </span>
                            </li>
                        @else
                            <li class="paginate_button page-item active">
                                <a href="{{ $url }}" class="page-link" aria-controls="DataTables_Table_0"
                                   data-dt-idx="0" tabindex="0"
                                >
                                    {{ $page }}
                                </a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="paginate_button page-item next disabled" id="DataTables_Table_0_next">
                    <span class="page-link">
                        {!! __('pagination.next') !!}
                    </span>
                </li>
            @else
                <li class="paginate_button page-item previous disabled" id="DataTables_Table_0_next">
                    <a href="#"
                       aria-controls="DataTables_Table_0"
                       data-dt-idx="0" tabindex="0"
                       class="page-link"
                    >
                        {!! __('pagination.next') !!}
                    </a>
                </li>
            @endif
        </ul>
    </div>
@endif
