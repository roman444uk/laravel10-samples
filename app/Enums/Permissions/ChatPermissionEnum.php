<?php

namespace App\Enums\Permissions;

enum ChatPermissionEnum: string
{
    use PermissionEnumTrait;

    /**
     * Default.
     */
    case VIEW_ANY = 'chat.view-any';
}
