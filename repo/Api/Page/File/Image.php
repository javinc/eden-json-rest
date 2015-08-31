<?php //-->

namespace Api\Page\File;

use Modules\Helper;

class Image extends \Page 
{
    /* Constants
    --------------------------------------------*/
    /* Public Properties
    --------------------------------------------*/
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