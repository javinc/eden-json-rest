<?php //-->

namespace Api\Page;

use Modules\Helper;
use Modules\Auth;
use Modules\Rest;
use Modules\JWT;

use Services\Test as T;
use Services\User;

use Exception;

class Test extends \Page 
{
    /* Constants
    --------------------------------------------*/
    /* Public Properties
    --------------------------------------------*/
    // public $auth = false;

    /* Protected Properties
    --------------------------------------------*/
    /* Public Methods
    --------------------------------------------*/
    public function getVariables()
    {   
        return Rest::resource(new T(), true);
    }

    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
}