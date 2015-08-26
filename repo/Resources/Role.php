<?php //-->

namespace Resources;

use Modules\Auth;
use Modules\Service;
use Modules\Helper;

class Role
{
    /* Constants
    --------------------------------------------*/
    /* Public Properties
    --------------------------------------------*/
    /* Protected Properties
    --------------------------------------------*/
    /* Private Properties
    --------------------------------------------*/
    public static $required = array(
        'create' => array(
            'name',
            'permissions'
        )
    );
    
    /* Public Methods
    --------------------------------------------*/
    public static function __callStatic($name, $args)
    {
        
        // search 
        if(isset(Helper::getParam()['search']) && Helper::getRequestMethod() == 'GET') {
            // rip search
            unset($options['search']);
        }

        $table = end(explode('\\', get_class()));
        return Service::$table($name, $args);
    }
    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
}