<?php //-->

require_once __DIR__.'/../vendor/autoload.php';

use Exception;
use Modules\Helper;
use Modules\Upload;
use Resources\File as F;

// auto invoke


/**
 * defines the starting point of every site call.
 * starts laying out how classes and methods are handled.
 *
 * @category   service
 * @author     javincX
 */
class App
{
    /* Constants
    --------------------------------------------*/
    /* Public Properties
    --------------------------------------------*/
    /* Protected Properties
    --------------------------------------------*/
    /* Private Properties
    --------------------------------------------*/
    /* Public Methods
    --------------------------------------------*/
    public function __construct()
    {
        eden('server')->add(function($request, $response) {
            // pass request and response as args on the controller
            // update Helper module
            // update Resource module
            // write a router and make it as a module
            // 

            $response->set('body', 'Hello World!');
        });
    }

    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
}
