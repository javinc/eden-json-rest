<?php //-->

namespace Api\Page;

use Modules\Helper;
use Resources\User;

class Register extends \Page 
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
        // public access for post
        if(Helper::getRequestMethod() == 'POST') {
            return User::create(Helper::getJson());
        }
        
        return Helper::error(array(
            'msg' => 'method not allowed'));
    }
    
    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
}