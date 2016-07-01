<?php

namespace Jerve;

class View
{
	private $view_dir;
	private $router;
	private $arr;

	public function
	__construct()
	{
		$this->view_dir = separator_format(ROOT . '/' . $GLOBALS['_JERVE_C']['view_dir']);
		$this->router = $GLOBALS['_JERVE_C']['router'];
	}

	public function
	render($tpl = "")
	{
		$c = $this->router->get_controller();
		if($tpl)
			$a = $tpl;
		else
			$a = $this->router->get_action();

		$file_path = separator_format($this->viewDir . "/" . $c . "/" . $a . ".html");
		if(file_exists($file_path)) {
			if(count($this->arr)) {
				foreach ($this->arr as $k => $each) {
					$$k = $each;
				}
			}
			require_once($file_path);
		}
		else
			die('模板不存在');

	}
	
	public function
	assign($name, $value)
	{
		$this->arr[$name] = $value;
	}
}