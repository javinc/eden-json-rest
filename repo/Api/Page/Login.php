<?php //-->

namespace Api\Page;

use Modules\Helper;
use Modules\JWT;
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
        if(Helper::getRequestMethod() == 'POST') {
            $payload = Helper::getJSON();

            // check required
            if($field = Helper::getMissingFields($payload, array(
                self::USER_FIELD, 
                self::PASS_FIELD))) {
                return Helper::error(
                    'LOGIN_FIELDS_REQUIRED',
                    $field . ' required, empty given');
            }

            // hash password
            $payload[self::PASS_FIELD] = sha1($payload[self::PASS_FIELD]);
            
            $user = User::get(array('filters' => $payload));
            
            // invalid
            if(!$user) {
                return Helper::error(
                    'LOGIN_INVALID',
                    'Invalid username or password');
            }

            // disabled user no login
            if($user[self::STATUS_FIELD] == 'disabled') {
                return Helper::error(
                    'LOGIN_DISABLED',
                    'User is disabled');
            }

            // remove fields
            unset($user[self::PASS_FIELD]);

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

            // generate JWT
            $user['token'] = JWT::encode(array('user' => $user));

            return $user;
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