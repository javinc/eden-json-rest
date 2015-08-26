<?php //-->

namespace Modules;

use Resources\Channel as C;

class Channel
{
    /* Constants
    --------------------------------------------*/
    /* Public Properties
    --------------------------------------------*/
    /* Protected Properties
    --------------------------------------------*/
    /* Public Methods
    --------------------------------------------*/
    public static function push($name, $data)
    {
        // validate
        if(empty($name) || empty($data)) {
            return Helper::error(array(
                'msg' => 'required fields are required',
                'fields' => array('name', 'data')));
        }

        return C::create(array(
            'user_id' => self::getUser(),
            'name' => $name,
            'data' => json_encode($data)));
    }

    public static function pull($name, $index = 0)
    {   
        // validate
        if(empty($name)) {
            return Helper::error(array(
                'msg' => 'name field is required'));
        }

        // set index to 0 if null
        if(empty($index)) {
            $index = 0;
        }

        // build option and search
        $result = C::find(array(
            'filters' => array(
                'name' => $name,
                array('id > %d', $index))
        ));

        // extract data on data field
        // decode to data to json
        foreach($result as $key => $value) {
            $result[$key]['data'] = json_decode($value['data']);
            $result[$key]['index'] = $value['id'];
            unset($result[$key]['id']);
        }

        return $result;
    }

    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
    private static function getUser()
    {   
        // get user
        if($user = Auth::getUser()) {
            return $user['id'];
        }

        Helper::panic('User does not have Id');
    }
}