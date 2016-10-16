<?php //-->

require_once __DIR__.'/../vendor/autoload.php';

use Eden\Core\Argument;

use Modules\Auth;
use Modules\Helper;

/**
 * The starting point of every application call. If you are only
 * using the framework you can rename this function to whatever you
 * like.
 *
 */
function app()
{
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
class App extends \Eden\Server\Index
{
    /* Constants
    --------------------------------------------*/
	/**
	 * @const int INSTANCE multiple or singleton
	 */
	const INSTANCE = 1;

	/**
	 * @const string uac key
	 */
	const USER_ACCESS_KEY = 'access';

    /* Public Properties
    --------------------------------------------*/
	/**
	 * @var string|null $rootPath
	 */
	public $rootPath = null;

	/**
	 * @var string|null $defaultDatabase
	 */
    public $defaultDatabase = null;

	/**
	 * @var string|null $defaultRegistry
	 */
    public $defaultRegistry = null;

    /* Protected Properties
    --------------------------------------------*/
    /* Private Properties
    --------------------------------------------*/
    /* Public Methods
    --------------------------------------------*/

	/**
	 * Sets the application absolute paths
	 * for later referencing
	 *
	 * @return Eve\Framework\Index
	 */
	public function defaultPaths()
	{
		// project path
		$root = __DIR__;
		if(!$this->registry()->isKey('path', 'root')) {
			$this->registry()->set('path', 'root', $root);
		}
		$root = $this->registry()->get('path', 'root');
		$paths = array(
			'config' => '../config',
			'upload' => '../upload',
			'vendor' => '../vendor',
			//PHP folders
			'controllers' => 'Controllers',
			'modules' => 'Modules',
			'resources' => 'Resources',
			'services' => 'Services',
			//Other Folders
			'public' => '../public');

		foreach($paths as $key => $path) {
			if(!$this->registry()->isKey('path', strtolower($key))) {
				$this->registry()->set('path', strtolower($key), $root . '/' . $path);
			}
		}

		// include files
		foreach ([
			'controllers',
			'modules',
			'resources',
			'services',
			] as $keyPath) {
			$this->includeFiles($this->registry()->get('path', $keyPath));
		}

		return $this;
	}

	/**
	 * Sets up the default database connection
	 *
	 * @param array|null $databases Inject a database config
	 *
	 * @return Eve\Framework\Index
	 */
	public function defaultDatabases(array $databases = null)
	{
		if(!$databases
			&& !empty($_SERVER)
			&& isset($_SERVER['HTTP_HOST'])
			&& strpos($_SERVER['HTTP_HOST'], 'testsuites') !== false
		) {
			$test = $this->config('test');
			$databases = $test['database'];
		}

		if(!$databases) {
			$databases = $this->config('databases');
		}

		foreach($databases as $key => $info) {
			//connect to the data as described in the settings
			switch($info['type']) {
				case 'postgre':
					$database = $this(
						'postgre',
						$info['host'],
						$info['name'],
						$info['user'],
						$info['pass']);
					break;
				case 'mysql':
					$database = $this(
						'mysql',
						$info['host'],
						$info['name'],
						$info['user'],
						$info['pass']);
					break;
				case 'sqlite':
					$database = $this('sqlite', $info['file']);
					break;
			}

			// Allow custom objects
			if (is_object($info['type'])) {
				$database = $info['type'];
			}

			$this->registry()->set('database', $key, $database);

			if($info['default']) {
				$this->defaultDatabase = $database;
			}
		}

		return $this;
	}

	/**
	 * Returns the current Registry
	 *
	 * @return Eden\Registry\Index
	 */
	public function registry()
	{
		if(!$this->defaultRegistry) {
			$this->defaultRegistry = $this('registry');
		}

		return $this->defaultRegistry;
	}

	/**
	 * Returns the absolute path
	 * given the key
	 *
	 * @param *string $key The path key name
	 *
	 * @return string
	 */
	public function path($key)
	{
		Argument::i()->test(1, 'string');

		return $this->registry()->get('path', $key);
	}

	/**
	 * Returns or saves the settings
	 * data given the key
	 *
	 * @param *string    $key  The settings file base name
	 * @param array|null $data The data to set in that name
	 *
	 * @return Eve\Framework\Index|array
	 */
	public function config($key, array $data = null)
	{
		Argument::i()->test(1, 'string');

		$path = $this->path('config');

		$file = $this('file')->set($path.'/'.$key.'.php');

		if(is_array($data)) {
			$file->setData($data);
			return $this;
		}

		if(!file_exists($file)) {
			return array();
		}

		return $file->getData();
	}

	/**
	 * Returns the default database instance
	 *
	 * @param string|null $key A specific database ID
	 *
	 * @return mixed
	 */
	public function database($key = null)
	{
		Argument::i()->test(1, 'string', 'null');

		if(is_null($key)) {
			//return the default database
			return $this->defaultDatabase;
		}

		return $this->registry()->get('database', $key);
	}

	/**
	 * Sets Dynamic routes base on the request
	 *
	 * @return Eve\Framework\Index
	 */
	public function defaultRouting($routeNameSpace = null)
	{
		//just call the parent
		$this->all('**', function($request, $response) {
			// register request data
			$this->registry()->set('request', $request);

			//if there is already a body or action
			if($response->isKey('body') || $request->isKey('action')) {
				//do nothing
				return;
			}

			// hijack request to fix preflight
			Helper::fixPreflight();

			//determine the route namespace
			$prefix = 'Controllers';
			$root = $this->registry()->get('path', 'controllers');

			$path = $request['path']['string'];
			$array = explode('/', $path);

			$variables = array();
			$action = null;
			$buffer = $array;
			while(count($buffer) > 1) {
				$parts = ucwords(implode(' ', $buffer));
				$parts = empty($parts) ? '/Index' : $parts;

				//try to see if it's callable
				$file = $root.str_replace(' ', '/', $parts).'.php';
				if(file_exists($file)) {
					$contents = $file;

					if(is_callable($contents)) {
						$action = $contents;
						break;
					}
				}

				//try to see if it's a class
				$class = $prefix.str_replace(' ', '\\', $parts);

				if(class_exists($class)) {
					$action = $class;
					break;
				}

				$variable = array_pop($buffer);
				array_unshift($variables, $variable);
			}

			if(!$action || !class_exists($action)) {
				$defaultAction = $this->registry()->get('config', 'default_action');

				if(!$defaultAction) {
					$defaultAction = 'Index';
				}

				$defaultAction = ucwords($defaultAction);

				//try to see if it's callable
				$file = $root.'/'.$defaultAction.'.php';

				if(file_exists($file)) {
					$contents = $file;
					if(is_callable($contents)) {
						$action = $contents;
					}
				}

				//try to see if it's a class
				$default = $prefix.'\\'.$defaultAction;
				if(class_exists($default)) {
					$action = $default;
				}
			}

			//set the variables if it has not been set
			if(!$request->isKey('segment')) {
				$request->set('segment', $variables);
			}

			//if we have an action
			if($action) {
				//set the action
				$response->set('action', $action);
			}
		});

		return $this;
	}

	/**
	 * Sets the PHP timezone
	 *
	 * @param *string $zone The timezone identifier
	 *
	 * @return Eve\Framework\Index
	 */
	public function defaultTimezone($zone = 'GMT')
	{
		$settings = $this->config('settings');

		date_default_timezone_set($settings['server_timezone']);

		return $this;
	}

	/**
	 * Starts a session
	 *
	 * @return Eve\Framework\Index
	 */
	public function defaultSession()
	{
		session_start();

		return $this;
	}

	public function server()
	{
		$this->all('**', function($request, $response) {
			// call Controllers
			$action = $response->get('action');

			// check uac
			$this->checkAuth($action);

			$data = $action::main($request, $response);

			// check status code if error
			if(isset($data['error'])) {
				$response->set('code', 400);
			}

			// default output json
			$output = json_encode($data);

			Helper::renderHeaders($request);

			$response->set('headers', 'Content-Type', 'application/json');
			$response->set('body', $output);
		});

		return $this;
	}

	public function checkAuth($obj) {
		if(!isset($obj::$auth) || $obj::$auth === true) {
			Auth::setUser(Auth::check());
		}

		// User access control settings
		$settings = $this->config('settings');
		if(Auth::getUser() && isset($settings['uac']) && $settings['uac']) {
			if(property_exists($obj, 'permissions')) {
				$this->checkPermission(
					Auth::getUser(),
					Helper::getRequestMethod(),
					$obj::$permissions
				);
			}
		}
	}

	public function checkPermission($user, $action, $list) {
		if(!isset($list[$action])) {
			return false;
		}

		if(!in_array($list[$action], $user[self::USER_ACCESS_KEY])) {
			Auth::errorCode('ACTION_FORBIDDEN');
		}

		return true;
	}
    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
	private function includeFiles($dir)
	{
		// include all php files
		foreach (glob($dir . '/*') as $file) {
			if (is_dir($file)) {
				$this->includeFiles($file);
				continue;
			}

		    if (is_file($file)) {
		        require_once $file;
		    }
		}
	}
}
