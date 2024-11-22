<?php

namespace App\Enums\Permissions;

enum CheckPermissionEnum: string
{
    use PermissionEnumTrait;

    /**
     * Default.
     */
    case VIEW_ANY = 'check.view-any';
    case VIEW = 'check.view';
    case CREATE = 'check.create';
    case UPDATE = 'check.update';
    case DELETE_ANY = 'check.delete-any';
    case DELETE = 'check.delete';
    case RESTORE = 'check.restore';
    case FORCE_DELETE = 'check.force-delete';

    /**
     * Additional.
     */
    case ACCEPT_OR_REJECT = 'check.accept-or-reject';
    case SEND_TO_VERIFICATION = 'check.send-to-verification';
    case RECALL_FROM_DOCTOR_VERIFICATION = 'check.recall-from-doctor-verification';
    case DELETE_FILES_SETUP = 'check.deleteFilesSetup';
}
