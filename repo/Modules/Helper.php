<?php //-->

namespace Modules;

use Exception;

/**
 * Utility for Structure
 *
 * @category   utility
 * @author     javincX
 */
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
    /*
     * get POST JSON data
     *
     * @param int index
     * @return array 
     */
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

    /*
     * get GET paramters data
     *
     * @param int index
     * @return array 
     */
    public static function getParam($field = null)
    {
        return self::getData(
            control()->registry()['get'],
            $field);
    }

    /*
     * get request URI 
     *
     * @param int index
     * @return array 
     */
    public static function getSegment($index = null)
    {
        return self::getData(
            control()->registry()['request']['variables'],
            $index);
    }

    /*
     * get request method wrapper 
     *
     * @return string
     */
    public static function getRequestMethod()
    {

        return self::getServer()['REQUEST_METHOD'];
    }

    /*
     * get PHP SERVER data 
     *
     * @return array
     */
    public static function getServer($index = null)
    {
        return self::getData(
            control()->registry()['server'],
            $index);
    }

    /*
     * get settings 
     *
     * @return array
     */
    public static function getSetting($index = null)
    {
        return self::getData(
            control()->config('/settings'),
            $index);
    }

    /*
     * string search index
     * will return false if not found
     *  
     * @param string needle
     * @param string stack
     * @return int index
     */
    public static function indexOf($needle, $string)
    {
        $index = strrpos($string, $needle);
        if($index === false) {
            return false;
        }

        return $index;
    }

    /*
     * exec needed headers
     *  
     */
    public static function getHeaders()
    {
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization');
        header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
    }

    /*
     * wrapper for throwing exception
     * 
     * @param string error message 
     */
    public static function throwError($msg = null)
    {
        throw new Exception($msg);
    }

    /*
     * panics are program errors
     * stops the program for fatal errors
     * 
     * @param string error message 
     */
    public static function panic($name, $msg)
    {   
        self::getHeaders();

        // it will die and flagged as panic
        self::error($name, $msg, true, true);
    }

    /*
     * gives formatted error
     * 
     * @param string error message 
     * @return array formatted error 
     */
    public static function error($name, $msg, $die = false, $panic = false)
    {
        $error = array('error' => array(
            'name' => $name,
            'msg' => $msg,
            'panic' => $panic));

        if($die) {
            die(json_encode($error));
        }

        return $error;
    }

    /*
     * prints data
     * 
     * @param scalar 
     * @param bool 
     */
    public static function debug($data = null, $die = false)
    {
        control()->inspect($data);

        if($die) {
            die();
        }
    }

    /*
     * check fields if exists
     * search array will return 0 if not found
     * 
     * @param array stack
     * @param string needle
     * @param string needle
     */
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

    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
    // get data based on index
    private static function getData($stack, $index = null)
    {
        if($index === null) {
            return $stack;
        }

        return isset($stack[$index]) ? $stack[$index] : null;
    }
}