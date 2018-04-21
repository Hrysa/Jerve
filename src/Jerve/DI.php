<?php

namespace Jerve;

class DI
{
	private $diPool = [];

	public function __construct()
	{
	
	}

	public function set($key, $value)
	{
		if (gettype($value) !== 'object')
		{
			throw new \Exception('Application::set value must be object.');
		}

		$this->diPool[$key] = $value;
	}

	public function get($key)
	{
		return $this->diPool[$key]();
	}
}
