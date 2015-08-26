<?php //-->

namespace Modules;

use Resources\NetworkPrefix;

class Helper
{
    /* Constants
    --------------------------------------------*/
    /* Public Properties
    --------------------------------------------*/
    /* Protected Properties
    --------------------------------------------*/
    /* Public Methods
    --------------------------------------------*/
    public static function getJson($field = null)
    {
        if($input = (array) json_decode(file_get_contents('php://input'))) {
            control()->registry()->set('input', $input);
        }

        $data = self::getData((array)control()->registry()['input'], $field);

        // check if invalid json
        if(empty($data) && $data !== null) {
            self::panic('Invalid Json');
        }

        return $data;
    }

    public static function getParam($field = null)
    {
        return self::getData(
            control()->registry()['get'],
            $field);
    }

    public static function getSegment($index = null)
    {
        return self::getData(
            control()->registry()['request']['variables'],
            $index);
    }

    public static function getRequestMethod()
    {
        return self::getServer()['REQUEST_METHOD'];
    }

    public static function getServer()
    {
        return control()->registry()['server'];
    }

    public static function indexOf($needle, $string)
    {
        $index = strrpos($string, $needle);
        if($index === false) {
            return false;
        }

        return $index;
    }

    // needed headers
    public static function getHeaders()
    {
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization');
        header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
    }

    // throw exception
    public static function throwError($msg = null)
    {
        throw new Exception($msg);
    }

    // stops the program, unexpected errors
    public static function panic()
    {   
        $msg = func_get_args();
        if(count($msg) == 0) {
            return;
        }

        self::getHeaders();

        die(json_encode(self::error(array(
            'panic' => implode(' ', $msg)))));
    }

    // displays error
    public static function fatal($msg)
    {   
        if(count($msg) == 0) {
            return;
        }

        self::getHeaders();

        die(json_encode(self::error($msg)));
    }

    // gives formatted error
    public static function error($msg)
    {
        return array('error' => $msg);
    }

    // dump
    public static function debug($data = null, $die = false)
    {
        control()->inspect($data);

        if($die) {
            die();
        }
    }

    // check fields if exists
    public static function getMissingFields($data, $required)
    {   
        foreach($required as $require) {
            // error
            if(!isset($data[$require])) {
                return $require;
                
                break;
            }
        }

        return;
    }

    // standardized sms number
    public static function normalizeNumber(&$number)
    {
        $prefixes = array(
            '+639',
            '639',
            '09');

        // check missing
        $prefix = false;
        foreach($prefixes as $key) {
            if(Helper::indexOf($key, $number) === 0) {
                $prefix = $key;

                break;
            }
        }

        if($prefix === false) {
            return $prefix;
        }

        $number = $prefixes[0] . substr($number, strlen($prefix));

        // check length
        if(strlen($number) != 13) {
            return false;
        }

        return true;
    }

    // detect network 
    public static function detectNetwork($number)
    {
        // normalize number when number is seem to be normal
        // I'll consider special numbers
        if(strlen($number) > 10 && !self::normalizeNumber($number)) {
            return false;
        }

        // get network prefix
        $prefix = substr($number, 3, 3);
        
        // find network
        $network = NetworkPrefix::get(array(
            'filters' => array('number' => $prefix),
            'fields' => array('network')));

        return $network['network'] ? $network['network'] : 'other';
    }

    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
    private static function getData($stack, $index = null)
    {
        if($index === null) {
            return $stack;
        }

        return isset($stack[$index]) ? $stack[$index] : null;
    }
}