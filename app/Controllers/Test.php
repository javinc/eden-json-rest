<?php //-->

namespace Controllers;

use Modules\Rest;
use Services\Test as T;

class Test
{
    /* Constants
    --------------------------------------------*/
    /* Public Properties
    --------------------------------------------*/
    // public static $auth = false;

    /* Protected Properties
    --------------------------------------------*/
    /* Public Methods
    --------------------------------------------*/
    public static function main($request, $response)
    {
        return Rest::resource(new T(), true);
    }

    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
}
