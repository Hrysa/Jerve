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
			$this->path = explode('?',$this->get['s'])[0];
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

        $app_name = $part[0] ? $part[0] : 'Index';
        $this->controller = $part[1] ? $part[1] : 'Index';
        $this->action = $action = $part[2] ? $part[2] : 'index';

        $Controller = $this->app_path . '\\Controller\\' . $app_name . '\\' . $this->controller;

        if(class_exists($Controller)) {
            $ct = new $Controller();
            $ct->register($this->app);

            $prefix = strtolower($_SERVER['REQUEST_METHOD']);
            $restAction = $prefix;
            if(method_exists($ct, $restAction) && $this->action == 'index') {
                $apiFormat = $ct->apiFormat;
                if($apiFormat == 'xml') {
                    header("Content-type: application/xml");
                    // TODO
                    $res = $this->excute($ct, $restAction);
                    if($res)
                        echo xmlrpc_encode($res);
                } else {
                    $res = $this->excute($ct, $restAction);
                    if ($res)
                        echo json_encode($res);
                }
            }
            else if(method_exists($ct, $action)) {
                $res = $this->excute($ct, $action);
            }
            else
                throw new Exception("Action doesn't exists: $action", 1);
        } else {
            throw new Exception("Controller doesn't exists: $Controller");
        }

	}

	public function
    excute($ct, $action)
    {
        $method = new \ReflectionMethod($ct, $action);
        $params = $method->getParameters();
        $get = $this->get();
        $post = $this->post();
        $data = [];
        foreach($params as $each) {
            $tmp = '';
            if(isset($get[$each->name]))
                $tmp = $get[$each->name];

            if(isset($post[$each->name]))
                $tmp = $post[$each->name];

            $data[] = $tmp;
        }
        return $ct->$action(...$data);
    }

	private function
	parse_alias()
	{
		$index = $this->index;
		if(count($index)) {
			foreach($index as $k => $each) {
				if( (strpos($this->path, (string)$k.'/') === 0) || (strpos($this->path, (string)$k.'?') === 0)) {
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

        $params[1] = explode('=', $uri[1]);

        if($params[1][0])
            $this->get[$params[1][0]] = $params[1][1] ? $params[1][1] : '';
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
