<?php //-->
/*
 * This file is part of the Openovate Labs Inc. framework library
 * (c) 2013-2014 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */

use Modules\Helper;
use Resources\Permission;
use Modules\Auth;

/**
 * The base class for any class that defines a view.
 * A view controls how templates are loaded as well as 
 * being the final point where data manipulation can occur.
 *
 * @vendor Openovate
 * @package Framework
 */
class Page extends Eden\Block\Base 
{
	protected $body = array();

	protected $id = NULL;
	protected $title = NULL;
	
	protected $messages = array();

    public function __construct() {
        if(!isset($this->auth) || $this->auth === true) {
            Auth::setUser(Auth::check());
        }

        // User access control settings
        $settings = control()->config(control()->registry()->get('application').'/settings');
        if(Auth::getUser() && isset($settings['uac']) && $settings['uac']) {
            if(property_exists($this, 'permissions')) {
                $this::checkPermission(
                    Auth::getUser(),
                    Helper::getRequestMethod(),
                    $this::$permissions
                );
            }
        }
    }
	
	/**
	 * returns variables used for templating
	 *
	 * @return array
	 */
	public function getVariables() 
	{
		return $this->body;
	}
	
	/**
	 * Transform block to string
	 *
	 * @param array
	 * @return string
	 */
	public function render() 
	{
		Helper::getHeaders();

		die(json_encode($this->getVariables()));
	}
	
	protected function getHelpers() 
	{
		$urlRoot 	= control()->path('url');
		$cdnRoot	= control()->path('cdn');
		$language 	= control()->language();
		
		return array(
			'url' => function() use ($urlRoot) {
				echo $urlRoot;
			},
			
			'cdn' => function() use ($cdnRoot) {
				echo $cdnRoot;
			},
			
			'_' => function($key) use ($language) {
				echo $language[$key];
			});
	}

    public static function checkPermission($user, $action, $list) {
        if(!isset($list[$action])) {
            return false;
        }

        if(!Permission::checkPermission($user['role_id'], $list[$action])) {
            Auth::errorCode('ACTION_FORBIDDEN');
        }

        return true;
    }

}