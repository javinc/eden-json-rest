<?php //-->

namespace Api\Page;

use Modules\Helper;
use Modules\Rest;
use Resources\User as U;
use Resources\Permission;

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
    public static $permissions = array(
        'GET' => Permission::USER_VIEW,
        'POST' => Permission::USER_CREATE,
        'PATCH' => Permission::USER_UPDATE,
        'DELETE' => Permission::USER_REMOVE,
    );

    public function getVariables()
    {
        // restrict the username not to be change
        if(Helper::getRequestMethod() == 'PATCH') {
            if(Helper::getJson('username')) {
                return Helper::error(array(
                    'msg' => 'Username cannot be change'));
            }
        }

        // call to api
        return Rest::resource(new U(), true);
    }

    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
}