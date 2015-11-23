<?php //-->

namespace Controllers;

use Modules\Helper;
use Modules\Rest;
use Services\File as F;

class File
{
    /* Constants
    --------------------------------------------*/
    /* Public Properties
    --------------------------------------------*/
    /* Protected Properties
    --------------------------------------------*/
    /* Public Methods
    --------------------------------------------*/
    public static function exec()
    {
        // restrict the username not to be change
        if(Helper::getRequestMethod() == 'GET') {
            return Rest::resource(new F(), true);
        }

        return Helper::error('METHOD_NOT_ALLOWED', 'method not allowed');
    }

    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
}
