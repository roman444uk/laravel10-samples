<?php

namespace App\Enums\Permissions;

enum ProductionPermissionEnum: string
{
    use PermissionEnumTrait;

    /**
     * Default.
     */
    case VIEW_ANY = 'production.view-any';
    case VIEW = 'production.view';
    case CREATE = 'production.create';
    case UPDATE_ANY = 'production.update-any';
    case UPDATE = 'production.update';
    case DELETE = 'production.delete';
    case RESTORE = 'production.restore';
    case FORCE_DELETE = 'production.force-delete';

    /**
     * Additional.
     */
    case CHANGE_STATUS = 'production.change-status';
    case SEND_TO_WORK = 'production.send-to-work';
}
