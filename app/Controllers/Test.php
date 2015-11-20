<?php //-->

namespace App\Controllers;

use Modules\Rest;
use Services\Test as T;

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
