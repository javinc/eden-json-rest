<?php //-->

namespace Modules;

class Rest
{
    /* Constants
    --------------------------------------------*/
    const TYPE_FIELD = 'type';
    const ADMIN_TYPE = 'admin';

    /* Public Properties
    --------------------------------------------*/
    /* Protected Properties
    --------------------------------------------*/
    protected static $methodsAvailable = array(
        'GET' => 'find', 
        'POST' => 'create', 
        'PUT' => 'update', 
        'DELETE' => 'remove');

    /* Public Methods
    --------------------------------------------*/
    public static function resource($resource)
    {   
        return self::call($resource, Helper::getRequestMethod());
    }

    public static function call($resource, $method)
    {   
        // check empty resource || method
        if(empty($resource) || empty($method)) {
            Helper::panic(
                Helper::$resource . '::' . __FUNCTION__ . '()', 
                'resource & method are required,',
                'empty given');

            return;
        }

        // check available methods
        if(!array_key_exists($method, self::$methodsAvailable)) {
            Helper::panic($method, 'method not available');
        }

        // rest call
        return self::process($method, $resource, self::$methodsAvailable[$method]);
    }

    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
    private static function auth()
    {   
        // check user type
        // auth will identify type user
        // PUBLIC type user should only control
        // their own data by using id, while
        // ADMIN type user will not
        if($user = Auth::check()) {
            if(isset($user[self::TYPE_FIELD]) 
            && $user[self::TYPE_FIELD] == self::ADMIN_TYPE) {
                return self::ADMIN_TYPE;
            }
            
            return $user['id'];
        }
    }

    private static function process($method, $resource, $resourceMethod)
    {
        $method = strtolower($method);
        if(!$method) {
            Helper::panic($method, ' method not exists');
        }

        // check request authentication
        return self::$method($resource, $resourceMethod, self::auth());
    }

    private static function get($resource, $resourceMethod, $type)
    {   
        $options = Helper::getParam();
        
        // check user type
        if($type !== self::ADMIN_TYPE) {
            // exclude user

            if(Helper::indexOf('user', strtolower($resource))) {
                return $resource::get($type);
            }

            // add filters on options
            $options['filters']['user_id'] = $type;
        }

        // check if singles
        if($id = Helper::getSegment(0)) {
            $options['filters']['id'] = $id;
            $options['limits'] = [0, 1];

            return $resource::get($options);
        }

        return $resource::$resourceMethod($options);
    }

    private static function post($resource, $resourceMethod, $type)
    {   
        // check user type
        if($type !== self::ADMIN_TYPE) {
            // exclude user
            if(Helper::indexOf('user', strtolower($resource))) {
                Helper::panic('not allowed');
            }
        }

        // no id
        if((bool) Helper::getSegment(0)) {
            Helper::panic('Id must not define');
        }

        return $resource::$resourceMethod(Helper::getJson());
    }

    private static function put($resource, $resourceMethod, $type)
    {
        // check user type
        if($type !== self::ADMIN_TYPE) {
            return $resource::$resourceMethod(
                Helper::getJson(), $type);
        }

        // check if singles
        if($id = Helper::getSegment(0)) {
            return $resource::$resourceMethod(
                Helper::getJson(), $id);
        }

        Helper::panic('Id not defined');
    }

    private static function delete($resource, $resourceMethod, $type)
    {   
        // check user type
        if($type !== self::ADMIN_TYPE) {
            // this means deactivation
            return $resource::$resourceMethod($type);
        }

        // check if singles
        if($id = Helper::getSegment(0)) {
            return $resource::$resourceMethod($id);
        }

        Helper::panic('Id not defined');
    }
}