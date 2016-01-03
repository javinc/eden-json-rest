<?php //-->

namespace Controllers;

use Modules\Helper;

class Index
{
    /* Constants
    --------------------------------------------*/
    /* Public Properties
    --------------------------------------------*/
    public $auth = false;

    /* Protected Properties
    --------------------------------------------*/
    /* Public Methods
    --------------------------------------------*/
    public function render()
    {
        $response->set('body', 'from Controllers Index');
    }

    public static function process($request, $response)
    {
        $response->set('body', 'from Controllers Index');

        // return Helper::error('NOT_FOUND', 'page not found');
    }

    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
}
