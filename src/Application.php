<?php

namespace Jerve;
use Jerve\Routing\Router;
use Jerve\Middleware;

class Application
{
	private $basePath;

	private $appPath;

	private $router;

	private $middleware;

	public function __construct($basePath, $appPath = 'App')
	{
		$this->basePath = $basePath;
		$this->appPath = $appPath;
		$this->router = new Router($this);
		$this->autoload();
	}

	public function run()
	{
		$this->router->handleRequest();
	}

	public function get($path, $handle)
	{
		$this->router->middleware($this->middleware);
		$this->router->request('get', $path, $handle);
	}

	public function middleware(string $middleware)
	{
		if(!$this->middleware)
			$this->middleware = new Middleware;
		$this->middleware->add($middleware);
		return $this;
	}

	public function autoload()
	{
		Autoload::register($this->basePath . DIRECTORY_SEPARATOR);
	}
}
