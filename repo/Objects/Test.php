<?php //-->

namespace Objects;

use Modules\Resource;

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
        $table = end(explode('\\', get_class()));
        return Resource::$table($name, $args);
    }
    
    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
}