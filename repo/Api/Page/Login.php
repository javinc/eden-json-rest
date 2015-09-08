<?php //-->

namespace Api\Page;

use Modules\Helper;
use Services\User;

class Login extends \Page 
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
        // public access for post
        if(Helper::getRequestMethod() == 'POST') {
            return User::login(Helper::getJSON());
        }
        
        return Helper::error(
            'METHOD_NOT_ALLOWED',
            'method not allowed');
    }
    
    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
}