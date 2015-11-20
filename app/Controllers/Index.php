<?php //-->

namespace App\Controllers;

use Modules\Helper;

class Index extends \Page
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
    public function getVariables()
    {
        return Helper::error('NOT_FOUND', 'page not found');
    }

    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
}
