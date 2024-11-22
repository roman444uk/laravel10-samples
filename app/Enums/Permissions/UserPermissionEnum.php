<?php

namespace App\Enums\Permissions;

enum UserPermissionEnum: string
{
    use PermissionEnumTrait;

    /**
     * Default.
     */
    case VIEW = 'user.view';
    case CREATE = 'user.create';
    case UPDATE = 'user.update';
    case DELETE = 'user.delete';
    case RESTORE = 'user.restore';
    case FORCE_DELETE = 'user.force-delete';
}
