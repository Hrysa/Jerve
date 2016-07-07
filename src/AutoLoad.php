<?php

namespace Jerve;

class AutoLoad
{
	private $path;

	public function
	auto_load_register($path)
	{
		$this->path = $path;
		spl_autoload_register(array('Jerve\AutoLoad', "auto_load"));
	}

	public function
	auto_load($class)
	{
		$path = explode("\\", $class);
		$path = implode(DIRECTORY_SEPARATOR, $path);
		$file = $this->path . DIRECTORY_SEPARATOR . $path . ".php";
		if(file_exists($file))
			require_once($file);
	}
}