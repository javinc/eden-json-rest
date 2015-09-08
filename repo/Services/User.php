<?php //-->

namespace Services;

use Resources\User as U;

class User
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
        return U::$name(current($args), end($args));
    }
    
    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
}