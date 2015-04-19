<?php //-->
/*
 * This file is part of the Openovate Labs Inc. framework library
 * (c) 2013-2014 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */

use Modules\Helper;

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
	const TEMPLATE_EXTENSION = 'php';
	
	protected $meta	= array();
	protected $head = array();
	protected $body = array();
	protected $foot = array();
	
	protected $id = NULL;
	protected $title = NULL;
	
	protected $messages = array();
	
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
	 * returns location of template file
	 *
	 * @return string
	 */
	public function getTemplate() 
	{
		if(!$this->template) {
			$start = strrpos(get_class($this), '\\');
			
			$this->template = control('type', get_class($this))
				->str_replace('\\', DIRECTORY_SEPARATOR)
				->substr($start)
				->strtolower().'.'
				.static::TEMPLATE_EXTENSION;
		}
		
		return $this->template;
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
	
	/**
	 * Adds flash messaging
	 *
	 * @param string
	 * @param string
	 * @return Front\Page
	 */
	protected function addMessage($message, $type = 'info') 
	{
		$_SESSION['messages'][] = array(
		'type' 		=> $type,
		'message' 	=> $message);
		
		return $this;
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
}