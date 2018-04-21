<?php

namespace Jerve;

class Middleware
{
	private $middlewarePool;

	public function __construct()
	{
	
	}

	public function add(string $middleware)
	{
		$this->middlewarePool[] = $middleware;
	}

	public function getMiddlewares()
	{
		return $this->middlewarePool;
	}

	public function clean()
	{
		$this->middlewarePool = [];
		return true;
	}

	public static function handle($middlewarePool, &$router)
	{
		$class = '\\' . $middlewarePool[0];
		$middleware = new $class;
		$middleware->handle(function() use($router) {
			$router->dropMiddleware();
			$router->handleRequest();
		});
	}
}
