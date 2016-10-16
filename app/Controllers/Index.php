<?php //-->

namespace Controllers;

use Modules\Helper;

class Index
{
    /* Constants
    --------------------------------------------*/
    /* Public Properties
    --------------------------------------------*/
    public static $auth = false;

    /* Protected Properties
    --------------------------------------------*/
    /* Public Methods
    --------------------------------------------*/
    public static function main($request, $response)
    {
        return Helper::error('NOT_FOUND', 'page not found');
    }

    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
}
