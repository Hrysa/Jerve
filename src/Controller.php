<?php

namespace Jerve;

class Controller
{
	protected $db;

	protected $urlParams;

	public function
	__construct()
	{
		$this->db = $GLOBALS['_JERVE_C']['db'];
		$this->view = new View();
		$this->router = $GLOBALS['_JERVE_C']['router'];

	}

	public function
	render($tpl = "")
	{
		$this->view->render($tpl);
	}

	public function
	assign($name, $value)
	{
		if($name && ($value !== false))
			$this->view->assign($name, $value);
	}
}