<?php //-->

namespace Modules;

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
        $data = self::getData(
            (array) json_decode(file_get_contents('php://input')),
            $field);

        // check if invalid json
        if(empty($data)) {
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
        if ($index === false) {
            return false;
        }

        return $index;
    }

    // needed headers
    public static function getHeaders()
    {
        header('Content-Type: application/json');
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization");
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
        foreach ($required as $require) {
            // error
            if(!isset($data[$require])) {
                return $require;
                
                break;
            }
        }

        return;
    }

    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
    private static function getData($stack, $index)
    {   
        if($index !== null && isset($stack[$index])) {
            return $stack[$index];
        }

        return $stack;
    }
}