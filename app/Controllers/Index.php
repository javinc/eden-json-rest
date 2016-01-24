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
        Helper::panic('NOT_FOUND', 'page not found');

        $response->set('body', 'from Controllers Index');
    }

    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
}
