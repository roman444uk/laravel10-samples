<!-- Page Header -->
<div class="page-header">
    <div class="row">
        <div class="col-sm-12">
            <ul class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ url('/') }}">{{ __('Панель') }} </a>
                </li>
                @foreach($crumbs as $index => $crumb)
                    <li class="breadcrumb-item">
                        <i class="feather-chevron-right"></i>
                    </li>
                    <li class="breadcrumb-item active">
                        @if(isset($crumb['url']))
                            <a href="{{ $crumb['url'] }}">{{ $crumb['label'] }}</a>
                        @else
                            {{ $crumb['label'] }}
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
<!-- /Page Header -->
