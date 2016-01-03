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
    // public $auth = false;

    /* Protected Properties
    --------------------------------------------*/
    /* Public Methods
    --------------------------------------------*/
    public function exec()
    {
        return Rest::resource(new T(), true);
    }

    public static function process($request, $response)
    {
        $response->set('body', 'from Controllers Test');

        // return Helper::error('NOT_FOUND', 'page not found');
    }

    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
}
