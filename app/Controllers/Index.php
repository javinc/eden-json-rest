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

    public static function main($request, $response)
    {
        return Helper::error('NOT_FOUND', 'page not found');
    }

    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
}
