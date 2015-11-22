<?php //-->

require_once __DIR__.'/../vendor/autoload.php';

use Modules\Helper;

/**
 * The starting point of every application call. If you are only
 * using the framework you can rename this function to whatever you
 * like.
 *
 */
function app() {
	$class = App::i();
	if(func_num_args() == 0) {
		return $class;
	}

	$args = func_get_args();

	return $class->__invoke($args);
}

/**
 * Defines the starting point of every site call.
 * Starts laying out how classes and methods are handled.
 *
 * @vendor Openovate
 * @package Framework
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
        eden('server')
            ->route('*', function($request, $response) {
                // pass request and response as args on the controller
                // update Helper module
                // update Resource module
                // write a router and make it as a module
                //

                $response->set('body', 'Hello World!');
            })
            ->render();
    }

    public static function i()
    {

    }

    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
}
