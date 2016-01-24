<?php //-->

namespace Services;

use Modules\Auth;
use Modules\Helper;

/**
 * Service Me
 * business logic of this class object
 *
 * @category   service
 * @author     javincX
 */
class Me
{
    /* Constants
    --------------------------------------------*/
    const FB_URL = 'http://graph.facebook.com';

    /* Public Properties
    --------------------------------------------*/
    /* Protected Properties
    --------------------------------------------*/
    /* Private Properties
    --------------------------------------------*/
    /* Public Methods
    --------------------------------------------*/
    public static function get($userId = null)
    {
        if(empty($userId)) {
            $userId = Auth::getUser()['id'];
        }

        return User::get($userId);
    }

    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
}
