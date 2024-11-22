<?php

namespace App\Enums\Permissions;

enum StagePermissionEnum: string
{
    use PermissionEnumTrait;

    /**
     * Default.
     */
    case VIEW_ANY = 'stage.view-any';
    case VIEW = 'stage.view';
    case CREATE = 'stage.create';
    case UPDATE = 'stage.update';
    case DELETE_ANY = 'stage.delete-any';
    case DELETE = 'stage.delete';
    case RESTORE = 'stage.restore';
    case FORCE_DELETE = 'stage.force-delete';

    /**
     * Additional.
     */
    case CHANGE_EMPLOYEES = 'stage.change-employees';
    case CHANGE_STATUS = 'stage.change-status';
    case FINISH_TREATMENT = 'stage.finish-treatment';
    case INITIATE_TREATMENT = 'stage.initiate-treatment';
    case ORDER_CONTINUATION = 'stage.order-continuation';
    case REFUSE_TREATMENT = 'stage.refuse-treatment';
    case SEND_TO_WORK = 'stage.send-to-work';
}
