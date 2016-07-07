<?php
namespace Jerve;

use Exception;
use Jerve\View;

class Router
{	
	private $uri;

	private $index;
	
	private $controller;
	
	private $action;
	
	private $params;

	private $path;

	private $get;

	private $app_path;

	private $View;

	public function
	__construct()
	{
		$_J = &$GLOBALS['_J'];
		$this->root_path = $_J['root_path'];
		$this->app_path = $_J['app_path'];

		$this->uri = $_SERVER['QUERY_STRING'];
		if(count($_GET)) {
			$this->get = $_GET;
			if(strpos($this->get['s'], "/") === 0)
				$this->get['s'] = substr($this->get['s'], 1);
			$this->path = $this->get['s'];
		} else {
			$this->path = "index";
		}
	}

	public function
	get_uri()
	{
		return $this->url;
	}

	public function
	set($index, $value = "")
	{
		if(is_array($index)) {
			foreach($set as $each)
			{
				$this->index[$each[0]] = $each[1];
			}
		} else {
			$this->index[$index] = $value;
		}
	}

	public function
	dispatch()
	{
		$this->parse_alias();

		if(is_callable($this->path)) {
			$path = $this->path;
			$result = $path();

			if(is_string($result)) {
				$this->path = $result;
				$this->parse_alias();
			} else
			return $result;
		}

		$part = explode('/', $this->path);

		if(count($part) >= 2) {
			$this->controller = $part[0];
			$this->action = $action = $part[1];
			$Controller = $this->app_path . '\\Controller\\' . $this->controller;
			try{
				if(class_exists($Controller)) {
					$ct = new $Controller();
					
					if(method_exists($ct, $action))
						$ct->$action();
					else
						throw new Exception("Action doesn't exists. $Action", 1);
				} else {
					throw new Exception("Controller doesn't exists. $Controller");
				}
			} catch (Exception $e) {
				echo $e->getMessage();
			}
		} else {
			try{
				throw new Exception("Wrong URL.", 1);
			} catch (Exception $e){
				echo $e->getMessage();
			}
			
		}
	}

	private function
	parse_alias()
	{
		$index = $this->index;

		if(count($index)) {
			foreach($index as $k => $each) {
				if( (strpos($this->path, $k) === 0))
					$this->path = $each;
			}
		}
	}

	public function
	view($path)
	{
		if(is_object($this->View))
			$this->View->render($path); 
		else {
			$this->View = new View();
			$this->View->set('Controller', $this->controller);
			$this->View->set('Action', $this->action);
			$this->View->render($path); 
		}
	}

	public function
	get_controller()
	{
		return $this->controller;
	}

	public function
	get_action()
	{
		return $this->action;
	}

	public function
	get_params()
	{
		return $this->params;
	}

	private function
	parse_params()
	{
		if(is_callable(name))
		// alias router
		if(count($this->index)) {
			$index = $this->index;
			foreach($index as $k => $each) {
				if( (strpos($this->path, $k) === 0)) {
					$params = substr(str_replace($k, '', $this->path), 1);
				}
			}
		}
		// direct router
		if(!isset($params)) {
			$path = explode("/", $this->path);
			unset($path[0]);
			unset($path[1]);
			$params = implode("/",$path);
		}

		if(isset($params)) {
			$params = explode("/", $params);
			$tmp = [];
			for($i = 0; $i < count($params); $i+=2)
			{
				if($params[$i])
					$tmp[$params[$i]] = isset($params[$i+1]) ? $params[$i+1] : 0;
			}
			$params2 = explode("&", $this->uri);
			unset($params2[0]);
			foreach($params2 as $each) {
				$v = explode("=", $each);
				$tmp[$v[0]] = $v[1];
			}
			$this->params = $tmp;
		}
	}
}