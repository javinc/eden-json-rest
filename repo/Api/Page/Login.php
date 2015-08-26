<?php //-->

namespace Api\Page;

use Modules\Helper;
use Resources\User;
use Resources\RolePermission;

class Login extends \Page 
{
    /* Constants
    --------------------------------------------*/
    const USER_FIELD = 'username';
    const PASS_FIELD = 'password';
    const STATUS_FIELD = 'status';

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
        if(Helper::getRequestMethod() == 'GET') {
            $option = Helper::getParam();

            // check required
            if($field = Helper::getMissingFields($option, array(
                self::USER_FIELD, 
                self::PASS_FIELD))) {
                return Helper::error(array(
                    'msg' => $field . ' required, empty given'));
            }

            // hash password
            $option[self::PASS_FIELD] = sha1($option[self::PASS_FIELD]);
            
            $user = User::get(array('filters' => $option));
            
            // invalid
            if(!$user) {
                return Helper::error(array(
                    'msg' => 'Invalid username or password',
                    'errorLogin' => true));
            }

            // disabled user no login
            if($user[self::STATUS_FIELD] == 'disabled') {
                return Helper::error(array(
                    'msg' => 'User is disabled',
                    'errorLogin' => true));
            }

            unset($user[self::PASS_FIELD]);

            // generate token
            $user['token'] = base64_encode($user[self::USER_FIELD] . ':' . 
                sha1($user[self::USER_FIELD]));
            $user['errorLogin'] = false;

            // get permissions
            $permissions = RolePermission::find(array(
                'filters' => array(
                    'role_id' => $user['role_id']),
                'relate' => array('permission')));

            // stack permission
            $user['access'] = array();
            foreach($permissions as $permission) {
                $user['access'][] = $permission['permission']['name'];
            }

            return $user;
        }
        
        return Helper::error(array(
            'msg' => 'method not allowed'));
    }
    
    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
}