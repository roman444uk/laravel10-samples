@php
    use App\Classes\Helpers\Db\StageHelper;
    use App\Classes\Helpers\Db\StageStatusHelper;
    use App\Enums\Orders\StageStatusEnum;
    use App\Support\Str;

    /**
     * @var string $slot
     */

    $slot = StageStatusHelper::getStatusLinkChronological($slot, getUserRole());
@endphp

<x-custom-badge @class(['status-' . StageStatusHelper::getStatusColor($slot)])>
    {{ StageStatusHelper::getStatusLabel($slot, getUserRole()) }}
</x-custom-badge>
