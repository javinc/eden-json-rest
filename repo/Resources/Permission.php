<?php //-->

namespace Resources;

use Modules\Auth;
use Modules\Service;
use Modules\Helper;

class Permission
{
    /* Constants
    --------------------------------------------*/
    const USER_VIEW = 'user_view';
    const USER_CREATE = 'user_create';
    const USER_UPDATE = 'user_update';
    const USER_REMOVE = 'user_remove';

    /* Public Properties
    --------------------------------------------*/
    /* Protected Properties
    --------------------------------------------*/
    /* Private Properties
    --------------------------------------------*/
    /* Public Methods
    --------------------------------------------*/
    public static function __callStatic($name, $args)
    {
        $table = end(explode('\\', get_class()));
        return Service::$table($name, $args);
    }

    public static function checkPermission($roleId, $permission) {
        if(is_string($permission)) {
            $permission = Service::db()->search('permission')
                ->filterByName($permission)
                ->getRow();

            $permission = $permission['id'];
        }

        return !!Service::db()->search('`role_permission`')
            ->setColumns('id')
            ->filterByRoleId($roleId)
            ->filterByPermissionId($permission)
            ->getRow();
    }
    
    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
}