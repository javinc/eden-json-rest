<?php //-->

namespace Api\Page;

use Modules\Helper;
use Resources\User;

class Login extends \Page 
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
        if(Helper::getRequestMethod() == 'GET') {
            $option = array(
                'filters' => array(
                    'fb' => Helper::getSegment(0)));
            return User::get($option);
        }
        
        return Helper::error(array(
            'msg' => 'method not allowed'));
    }
    
    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
}