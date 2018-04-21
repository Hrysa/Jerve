<?php

namespace Jerve;

class Autoload
{
	static $appPath;
	public function __construct()
	{
		
	}

	static public function register($appPath)
	{
		self::$appPath = $appPath;
		spl_autoload_register('Jerve\Autoload::autoload');
	}

	static public function autoload($class)
	{
		$path  = self::$appPath . DIRECTORY_SEPARATOR . $class . '.php';
		$path = str_replace('\\', '/', $path);
		if(file_exists($path))
			require_once $path;
	}
}
