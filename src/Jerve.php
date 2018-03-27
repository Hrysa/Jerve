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

	private $root_path;
	
	private $app_path;
	
	public function __construct($root_path, $app_path = "Apps")
	{
        $this->app_path = $app_path;
        $this->root_path = $root_path;
		$this->Router = new Router($this);
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
		if(is_array($i))
			$this->Router->set($i);
		else
			$this->Router->set($i, $v);
	}

	public function get_root_path() {
	    return $this->root_path;
    }

    public function get_app_path() {
	    return $this->app_path;
    }

    public function get_router() {
	    return $this->Router;
    }
}