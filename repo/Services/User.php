<?php //-->

namespace Services;

use Resources\User as U;

class User
{
    /* Constants
    --------------------------------------------*/
    /* Public Properties
    --------------------------------------------*/
    public static function __callStatic($name, $args)
    {   
        return call_user_method_array($name, new U(), $args);
    }

    public static function login()
    {
        return 'login';
    }

    /* Protected Properties
    --------------------------------------------*/
    /* Private Properties
    --------------------------------------------*/
    /* Public Methods
    --------------------------------------------*/
    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
}