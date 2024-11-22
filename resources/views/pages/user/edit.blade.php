@php
    use App\Models\City;use Illuminate\Support\Facades\View;

    /**
     * @var Illuminate\Support\Collection|City[] $citiesCollection
     */

    $editionDisabled = false;

    View::share('crumbs', [
        [
            'label' => __('users.users'),
            'url' => route('user.index'),
        ],
        [
            'label' => __('common.editing'),
        ],
    ]);
@endphp

@extends('layouts.main')

@section('content')
    @include('pages.user.form.index', [
        'user' => $user,
        'action' => route('user.update', ['user' => $user]),
        'doctorDetailsRequired' => false,
    ])
@endsection
