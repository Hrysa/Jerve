<?php

namespace Jerve\Http;

class Request
{
	public static $_SERVER;

	/**
	 * http resource path.
	 */
	private $path;

	public function __construct()
	{
		self::$_SERVER = $_SERVER;
	}

	public function getUri()
	{
		return self::$_SERVER['REQUEST_URI'];	
	}

	public function getMethod()
	{
		return self::$_SERVER['REQUEST_METHOD'];
	}

	public function getPath()
	{
		$uri = $this->getUri();
		$this->path = $this->path ? $this->path : explode('?', $uri)[0];
		return $this->path;
	}
}
