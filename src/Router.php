<?php
namespace Jerve;

class Router
{	
	private $uri;

	private $index;
	
	private $controller;
	
	private $action;
	
	private $params;

	private $path;

	private $get;

	private $appPath;

	public function
	__construct()
	{
		$this->uri = $_SERVER['QUERY_STRING'];

		if(count($_GET)) {
			$this->get = $_GET;
			if(strpos($this->get['s'], "/") === 0)
				$this->get['s'] = substr($this->get['s'], 1);
			$this->path = $this->get['s'];
			$this->params = $this->parse_params();
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
	set($set)
	{
		foreach($set as $each)
		{
			$this->index[$each[0]] = $each[1];
		}
		$this->parse_params();
	}

	public function
	dispatch()
	{
		$this->parse_alias();
		$part = explode('/', $this->path);

		if(count($part) >= 2) {
			$this->controller = $part[0];
			$this->action = $action = $part[1];
			$Controller = $this->appPath . '\\' . $this->controller;
			if(class_exists($Controller)) {
				$ct = new $Controller();
				$exists = method_exists($ct, $action);
				if($exists)
					$ct->$action();
			}
		}
	}

	private function
	parse_alias()
	{
		if(count($this->index)) {
			$index = $this->index;
			foreach($index as $k => $each) {
				if( (strpos($this->path, $k) === 0))
					$this->path = $each;
			}
		}
	}

	public function
	set_app_path($path)
	{
		$this->appPath = basename($path);
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