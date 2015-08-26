<?php //-->

namespace Api\Page;

use Modules\Helper;
use Modules\Channel as C;

class Channel extends \Page 
{
    /* Constants
    --------------------------------------------*/
    /* Public Properties
    --------------------------------------------*/
    /* Protected Properties
    --------------------------------------------*/
    /* Public Methods
    --------------------------------------------*/
    public function getVariables()
    {   
        $param = Helper::getParam();

        // pulling
        if(Helper::getRequestMethod() == 'GET') {
            // check required
            if(isset($param['name'])) {
                return C::pull($param['name'], $param['index']);
            }

            return Helper::error(array(
                'msg' => 'GET name field is required'));

        // pushing
        } else if(Helper::getRequestMethod() == 'POST') {
            // check required
            $data = Helper::getJson();
            if(isset($param['name']) && !empty($data)) {
                return C::push($param['name'], $data);
            }

            return Helper::error(array(
                'msg' => 'required fields are empty',
                'fields' => array(
                    'GET' => array('name'),
                    'POST JSON' => array('data'))));
        }
        
        return Helper::error(array(
            'msg' => 'method not allowed'));
    }

    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
}