<?php
/*
 * Jerve library core file.
 *
 */
namespace Jerve;
 
use Jerve\AutoLoad;

class Jerve
{
	private $Router;

	private $conf;
	
	private $root_path;
	
	private $app_path;

	private $Db;

	public function __construct($root_path, $app_path = "App")
	{
		global $_J;
		$_J['root_path'] = $this->root_path = $root_path;
		$_J['app_path'] = $this->app_path = $app_path;
		$_J['Router'] = $this->Router = new Router();
		$_J['Db'] = "";
	}

	public function
	run()
	{
		$autoload = new AutoLoad();
		$autoload->auto_load_register($this->root_path);
		$this->Router->dispatch();
	}

	public function
	set($param, $value)
	{
		switch ($param) {
			case 'db':
				$value = $value();
				$_J = &$GLOBALS['_J'];
				$_J['Db'] = $value;
				break;
		}
		$this->$param = $value;
	}

	public function
	router($i = "", $v = "")
	{
		if(!$i)
			return $this->Router;
		$this->Router->set($i, $v);
	}
}