<?php

namespace Jerve;

class Controller
{
	protected $Db;

	protected $app;

	protected $root_path;

	protected $app_path;

	protected $Router;

	protected $View;

	protected $Cache;

	public $apiFormat = 'json';

	public function
	__construct()
	{

	}

	public function
    register($app)
    {
        $this->app = $app;
        $this->root_path = $app->get_root_path();
        $this->app_path = $app->get_app_path();
        $this->Router = $app->get_router();

    }

	public function
	render($tpl = "")
	{
        if(!$this->View)
            $this->View = new View($this->app);

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