<?php //-->

namespace Resources;

use Modules\Auth;
use Modules\Resource;
use Modules\Helper;

class RolePermission
{
    /* Constants
    --------------------------------------------*/
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
        return Resource::$table($name, $args);
    }

    public static function find($options)
    {
        $result = self::i()->find($options);

        if(!empty($options['relate']) && is_array($options['relate'])) {
            foreach($result as $key => $arr)  {
                if(in_array('permission', $options['relate'])) {
                    $result[$key]['permission'] = Permission::get($arr['permission_id']);
                    unset($result[$key]['permission_id']);
                }

                if(in_array('role', $options['relate'])) {
                    $result[$key]['role'] = Role::get($arr['role_id']);
                    unset($result[$key]['role_id']);
                }
            }
        }

        return $result;
    }

    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
}