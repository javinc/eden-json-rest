<?php //-->

namespace Api\Page;

use Modules\Helper;
use Modules\Rest;
use Resources\User as U;

class User extends \Page 
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
        // call to api
        return Rest::resource(new U());
    }
    
    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
}