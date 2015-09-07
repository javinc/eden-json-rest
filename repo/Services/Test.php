<?php //-->

namespace Services;

use Objects\Test as T;

class Test
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
        // return call_user_func_array('T::findx', $args);
        // return call_user_func_array(array($x, $method), $args);

        // return call_user_func_array('T::' . $method . '()', $args);

        // return forward_static_call_array(array(T, $method), $args);

        return T::$name();
    }

    public static function findx()
    {   
        return 'you are finding something';
    }

    public static function Jwt()
    {   
        return 'new T()';
    }

    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
}