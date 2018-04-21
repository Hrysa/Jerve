<?php

namespace Jerve\Routing;
use Jerve\Http\Request;
use Jerve\Middleware;

class Router
{
	private $middlewarePool;

	private $routerMap = [];

	private $handleParameters;

	public function __construct(&$app)
	{
		$this->app = $app;
		$this->request = new Request;
	}

	public function request($method, $path, $handle)
	{
		$method = strtoupper($method);
		if(!isset($this->routerMap[$method]))
		{
		$this->routerMap[$method] = [];
		}
		$this->routerMap[$method][$path] = $handle;
	}

	public function handleRequest()
	{
		$handle= $this->getRequestHandle();
		if(!$handle)
		{
			throw new \Exception('Url not Found', 404);
		}
		if($this->middlewarePool)
		{
			return Middleware::handle($this->middlewarePool, $this);
		}
		$args = $this->geneHandleParams($handle);
		$result = $handle(...$args);
		if(gettype($result) == 'array')
		{
			$class = $result[0];
			$action = $result[1];
			$instance = new $class(...$args);
			$instance->$action();
		}
	}

	public function geneHandleParams($handle)
	{
		$reflection = new \ReflectionFunction($handle);
		$arguments = $reflection->getParameters();
		$parameters = [];
		foreach($arguments as $each)
		{
			if($value = $this->handleParameters[$each->name])
				$parameters[] = $value;
			else
				$parameters[] = NULL;
		}
		return $parameters;
	
	}

	public function getRequestHandle()
	{
		$path = $this->request->getPath();
		$method = $this->request->getMethod();
		$pathPool = array_keys($this->routerMap[$method]);
		$parser = new Parser($path);
		foreach($pathPool as $each)
		{
			if($result = $parser->generate($each))
			{
				$this->handleParameters = $result;
				return $this->routerMap[$method][$each];
			}
		}
	}

	public function dropMiddleware()
	{
		array_shift($this->middlewarePool);
	}

	public function middleware($middleware)
	{
		if(gettype($middleware) !== 'object')
			return false;
		$this->middlewarePool = $middleware->getMiddlewares();
		$middleware->clean();
	}
}
