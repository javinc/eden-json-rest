<?php //-->

namespace Api\Page;

use Modules\Helper;
use Modules\Auth;
use Modules\Rest;
use Resources\User;

class Test extends \Page 
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
        return $this->testService();
        return Rest::resource(new User());
    }

    public function testService()
    {
        $auth = Auth::check();

        // $simple = User::get(26);

        // $single = User::get(array(
        //     'filters' => array(
        //         'id' => 31)));

        // $multi = User::find(array( 
            // 'filters' => array(
            //     'type' => 'publisher'),
            // 'fields' => ['id', 'email', 'name'],
            // 'sorts' => array('id' => 'desc'),
        //     'limits' => [0, 3]));

        // $create = User::create(Helper::getJson());
        // $update = User::update(
        //     Helper::getJson(),
        //     Helper::getSegment(0));

        // $remove = User::remove(26);

        return array(
            'auth' => $auth,
            'create' => $create,
            'update' => $update,
            'remove' => $remove,
            'simple' => $simple,
            'single' => $single,
            'multi' => $multi,
            'param' => Helper::getParam('sample'),
            // 'json' => Helper::getJson(),
            'segment' => Helper::getSegment(1),
            'error' => false);
    }

    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
}