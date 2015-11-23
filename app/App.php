<?php //-->

require_once __DIR__.'/../vendor/autoload.php';

use Eden\Core\Base as Core;

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
class App extends Core
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
    public function run()
    {
        try {
            $this('server')
                ->all('*', function($request, $response) {
                    // print_r($request);
                    // print_r($response);

                    Index::exec();

                    $response->set('body', 'Hello World!');
                })
                ->render();
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    /* Protected Methods
    --------------------------------------------*/
    protected function __construct()
    {
    }

    private function __clone()
    {
    }

    private function __wakeup()
    {
    }

    /* Private Methods
    --------------------------------------------*/
}
