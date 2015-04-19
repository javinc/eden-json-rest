<?php //-->

namespace Modules;

use Resources\User;

class Auth extends \Eden\Core\Base
{
    /* Constants
    --------------------------------------------*/
    const AUTH_USER = 'PHP_AUTH_USER';
    const AUTH_PW = 'PHP_AUTH_PW';
    const FB_FIELD = 'fb';

    /* Public Properties
    --------------------------------------------*/
    /* Protected Properties
    --------------------------------------------*/
    private static $errors = array(
        100 => 'no credentials found',
        101 => 'invalid credentials',
        200 => 'user not found');

    /* Public Methods
    --------------------------------------------*/
    public static function check()
    {
        $server = Helper::getServer();

        // check required
        if(!isset($server[self::AUTH_USER]) || !isset($server[self::AUTH_PW])) {
            self::erroCode(100);
        }

        $key = $server[self::AUTH_USER];
        $pass = $server[self::AUTH_PW];

        // validate and get id
        if(!self::validate($key, $pass)) {
            self::erroCode(101);
        }

        // check if user exists
        $user = User::get(array(
            'filters' => array(
                self::FB_FIELD => $key)));

        // if deleted or not exists
        if(!$user) {
            self::erroCode(200);
        }

        return $user;
    }

    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
    private static function erroCode($code)
    {   
        if(array_key_exists($code, self::$errors)) {
            Helper::fatal(array(
                'code' => $code,
                'msg' => self::$errors[$code]));
        }
    }

    private static function validate($key, $pass)
    {
        // pass is just a sha1 of key
        if(sha1($key) !== $pass) {
            return false;
        }

        return true;
    }
}