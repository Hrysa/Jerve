<?php
namespace Jerve;

use Exception;
use Jerve\View;

class Router
{
    private $app;

	private $uri;

	private $index;
	
	private $controller;
	
	private $action;
	
	private $params;

	private $path;

	private $path_alias;

	private $get;

	private $app_path;

	private $View;

	public function
	__construct($app)
	{
	    $this->app = $app;

		$this->root_path = $app->get_root_path();
		$this->app_path = $app->get_app_path();

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
			foreach($index as $each)
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
		$this->parse_params();
		$part = explode('/', $this->path);

		if(count($part) >= 2) {
			$this->controller = $part[0];
			$this->action = $action = $part[1];
			$Controller = $this->app_path . '\\Controller\\' . $this->controller . '\\' . $this->action;

				if(class_exists($Controller)) {
					$ct = new $Controller();
					$ct->register($this->app);
					//echo $_SERVER['REQUEST_METHOD'];
					//print_r($_SERVER);
                    $prefix = strtolower($_SERVER['REQUEST_METHOD']);

                    //header('Access-Control-Allow-Methods:PUT,POST,GET,DELETE,OPTIONS');

                    $restAction = $prefix;
                    if(method_exists($ct, $restAction)) {
                        $apiFormat = $ct->apiFormat;
                        if($apiFormat == 'xml') {
                            header("Content-type: application/xml");
                            // TODO
                            echo xmlrpc_encode($ct->$restAction());
                        } else
                            echo json_encode($ct->$restAction());

                    }
                    else if(method_exists($ct, $action))
                        $ct->$action();

					else
						throw new Exception("Action doesn't exists: $action", 1);
				} else {
					throw new Exception("Controller doesn't exists: $Controller");
				}

		} else {
				throw new Exception("Wrong URL.", 1);
		}
	}

	private function
	parse_alias()
	{
		$index = $this->index;
		if(count($index)) {
			foreach($index as $k => $each) {
				if( (strpos($this->path, (string)$k) === 0)) {
					$this->path_alias = $k;
					if(is_callable($each))
						$this->path = $each();
					else
						$this->path = $each;
                }
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
	get_params($k = false)
	{
		if($k !== false)
			return isset($this->params[$k]) ? $this->params[$k] : false;
		else
			return $this->params;
	}

	private function
	parse_params()
	{
        $uri = $this->get['s'];
        $uri = explode('?', $uri);


        if($this->path_alias)
			$path = $this->path_alias;
		else
			$path = $this->path;
		$params = explode('?', str_replace($this->path_alias, "", $this->get['s']));


        $params[0] = explode('/', $params[0]);
        $params[1] = explode('=', $params[1]);

        for($i = 1; $i < count($params[0]); $i+=2 ) {
            $this->get[$params[0][$i]] = $params[0][$i+1];
        }

        for($i = 0; $i < count($params[1]); $i+=2) {
            $this->get[$params[1][$i]] = $params[1][$i+1];
        }
        unset($this->get['s']);
		$this->params = $this->get;
	}

	public function
	redirect($action, $params = "")
	{
		if($params) {
			foreach($params as $k => &$each) {
				$each = $k . "=" . $each;
			}
			$params = implode("&", $params);
			$params = "?" . $params;
		}

		$uri = $action .$params;
		$url = 'http://' . $_SERVER['SERVER_NAME'] . '/' .$uri;
		header("Location: $url");
	}

	public function
    get($key = '')
    {
        if($key)
            return $this->params[$key];
        return $this->params;
    }

    public function
    post($key = '')
    {
        if($key)
            return $_POST[$key];
        return $_POST;
    }
}
