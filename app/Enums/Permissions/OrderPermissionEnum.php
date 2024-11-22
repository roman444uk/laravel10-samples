<?php

namespace App\Enums\Permissions;

enum OrderPermissionEnum: string
{
    use PermissionEnumTrait;

    /**
     * Default.
     */
    case VIEW = 'order.view';
    case CREATE = 'order.create';
    case UPDATE = 'order.update';
    case DELETE = 'order.delete';
    case RESTORE = 'order.restore';
    case FORCE_DELETE = 'order.force-delete';
}
