@php
    use Illuminate\Support\Facades\View;

    /**
     * @var App\Models\User $user
     * @var Illuminate\Support\Collection $citiesCollection
     */

    View::share('crumbs', [
        [
            'label' => __('users.profile'),
        ],
    ]);
@endphp

@extends('layouts.main')

@section('content')
    @include('pages.user.form.index', [
        'user' => $user,
        'action' => route('profile.update'),
        'doctorDetailsRequired' => true,
    ])
@endsection
