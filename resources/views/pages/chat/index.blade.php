@php
    use App\Models\Chat;
    use Illuminate\Database\Eloquent\Casts\Json;
    use Illuminate\Support\Facades\View;

    /**
     * @var Illuminate\Database\Eloquent\Collection|App\Models\Chat[] $chatsCollection
     */

    $editionDisabled = false;

    View::share('crumbs', [
        [
            'label' => __('orders.chat'),
        ],
    ]);
@endphp

@extends('layouts.main')

@section('content')
    <chat :user="{{ Json::encode(\App\Data\UserData::fromModel(getUser()->load('profile.fileAvatar'))) }}"
          :chats="{{ Json::encode($chatsCollection->map(function (Chat $chat) {
                return \App\Data\ChatData::fromModel($chat);
          })) }}"
    ></chat>
@endsection
