<?php //-->

namespace Controllers;

use Modules\Helper;
use Services\User\Login as L;

class Login
{
    /* Constants
    --------------------------------------------*/
    /* Public Properties
    --------------------------------------------*/
    public static $auth = false;

    /* Protected Properties
    --------------------------------------------*/
    /* Public Methods
    --------------------------------------------*/
    public function main()
    {
        // public access for post
        if(Helper::getRequestMethod() == 'POST') {
            $payload = Helper::getJSON();

            // check required
            if($field = Helper::getMissingFields($payload, array(
                'email',
                'password'))) {
                return Helper::error('LOGIN_FIELDS_REQUIRED',
                    $field . ' required, empty given');
            }

            return L::basic($payload['email'], $payload['password']);
        }

        return Helper::error('METHOD_NOT_ALLOWED', 'method not allowed');
    }

    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
}
