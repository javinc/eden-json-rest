<?php //-->

namespace Services;

use Resources\User as U;
use Modules\JWT;
use Modules\Helper;

class User
{
    /* Constants
    --------------------------------------------*/
    const USER_FIELD = 'username';
    const PASS_FIELD = 'password';
    const STATUS_FIELD = 'status';
    
    /* Public Properties
    --------------------------------------------*/
    /* Protected Properties
    --------------------------------------------*/
    /* Private Properties
    --------------------------------------------*/
    /* Public Methods
    --------------------------------------------*/
    public static function __callStatic($name, $args)
    {   
        return U::$name(current($args), end($args));
    }

    public static function login($payload)
    {
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
    
    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
}