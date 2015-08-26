<?php //-->

namespace Resources;

use Resources\Role;
use Modules\Auth;
use Modules\Service;
use Modules\Helper;

class User
{
    /* Constants
    --------------------------------------------*/
    /* Public Properties
    --------------------------------------------*/
    /* Protected Properties
    --------------------------------------------*/
    /* Private Properties
    --------------------------------------------*/
    public static $required = array(
        'create' => array(
            'username',
            'first',
            'last',
            'role_id',
            'password'
        )
    );
    /* Public Methods
    --------------------------------------------*/
    public static function __callStatic($name, $args)
    {   
        $table = end(explode('\\', get_class()));
        return Service::$table($name, $args);
    }

    public static function create($data)
    {
        // check password
        if(empty($data['password']) || empty($data['username'])) {
            return Helper::error(array(
                'msg' => sprintf('Password and Username required')));
        }

        // check if user exists
        $options = array(
            'filters' => array('username' => $data['username']),
            'fields' => array('id'));

        if(self::i()->get($options)) {
            return Helper::error(array(
                'msg' => sprintf('Username "%s" already exists.', $data['username'])));
        }

        // check if valid role id
        if(!Role::i()->get($data['role_id'])) {
            return Helper::error(array(
                'msg' => sprintf('Invalid role id "%d".', $data['role_id'])));
        }

        $data['password'] = sha1($data['password']);

        // save the user
        $user = self::i()->create($data);

        return $user;
    }

    public static function update($data, $filter)
    {   
        // check password if exists then update it 
        // else dont
        if(isset($data['password']) && trim($data['password']) != '') {
            $data['password'] = sha1($data['password']);
        } else if(trim($data['password']) == '') {
            unset($data['password']);
        }

        // save the user
        $user = self::i()->update($data, $filter);

        return $user;
    }

    public static function find($options)
    {
        $result = self::i()->find($options);

        if(!empty($options['relate']) && is_array($options['relate'])) {
            foreach($result as $key => $arr)  {
                if(in_array('role', $options['relate'])) {
                    $result[$key]['role'] = Role::get($arr['role_id']);
                    unset($result[$key]['role_id']);
                }
            }
        }

        return $result;
    }
    
    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
}