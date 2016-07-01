<?php

namespace Jerve;

class Controller
{
	protected $Db;

	protected $root_path;

	protected $app_path;

	protected $Router;

	protected $View;

	protected $Cache;


	public function
	__construct()
	{
		$this->_register();
	}

	public function
	_register()
	{
		$_J = &$GLOBALS['_J'];
		$this->root_path = $_J['root_path'];
		$this->app_path = $_J['app_path'];
		$this->Router = $_J['Router'];
		$this->Db = $_J['Db'];
		$this->View = new View();
	}

	public function
	render($tpl = "")
	{
		$this->View->render($tpl);
	}

	public function
	assign($name, $value)
	{
		if($name && ($value !== false))
			$this->View->assign($name, $value);
	}

	public function
	Cache($expire_time = 5)
	{
		if(!is_object($this->Cache))
			$this->Cache = new Cache($this->View, $expire_time);
	}
}