<?php

namespace Jerve;

use Exception;

class View
{
	private $root_path;

	private $app_path;

	private $controller;

	private $action;

	private $assign_vars;

	private $Cache;

	public function
	__construct()
	{
		$_J = &$GLOBALS['_J'];
		$this->root_path = $_J['root_path'];
		$this->app_path = $_J['app_path'];
		$this->controller = $_J['Router']->get_controller();
		$this->action = $_J['Router']->get_action();
	}

	public function
	render($tpl = "")
	{
		try{
			if(!($file_path = $this->get_file_path($tpl)))
				throw new Exception("can't render this tpl.", 1);
			if(file_exists($file_path)) {
				if(count($this->assign_vars)) {
					foreach ($this->assign_vars as $k => $each) {
						$$k = $each;
					}
				}
				
				ob_start();
				@require_once($file_path);
				$data =  ob_get_contents();
				ob_end_clean();
				//$data = $this->res_merge($data);
				echo $data;

				if(is_object($this->Cache))
					$this->Cache->cache($data);
			}
			else
				throw new Exception("template file doesn't exists. $file_path", 1);
		} catch (Exception $e){
			echo $e->getMessage();
		}

	}

	public function
	get_file_path($tpl)
	{
		$tpl = explode("/", $tpl);
		if(count($tpl) == 1)
			$file_path = $this->root_path . DIRECTORY_SEPARATOR . $this->app_path . DIRECTORY_SEPARATOR . "View" . DIRECTORY_SEPARATOR . $this->controller . DIRECTORY_SEPARATOR . $this->action . ".html";
		else if(count($tpl) > 1) {
			$file_path = $this->root_path . DIRECTORY_SEPARATOR . $this->app_path . DIRECTORY_SEPARATOR . "View" . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $tpl) . ".html";
		} else
			return false;
		return $file_path;
	}

	public function
	res_merge($data)
	{
		// css
		$pattern = "/<link[\b-~][^>]+href=.([\b-~][^>]+css)[^>]+>/";
		$data = $this->preg_merge($pattern, $data, 'style');

		// js
		$pattern = "/<script[\b-~][^>]+src=.([\b-~][^>]+js)[^>]+>/";
		$data = $this->preg_merge($pattern, $data, 'script');

		return $data;
	}

	public function
	preg_merge($pattern, $data, $label)
	{
		if(preg_match_all($pattern, $data, $matchs)) {
			foreach($matchs[1] as $k => $each) {
				$res = "<$label>" . file_get_contents($_SERVER['DOCUMENT_ROOT'] . $each) . "</$label>";
				$data = str_replace($matchs[0][$k], $res, $data);
			}
		}
		return $data;
	}

	public function
	assign($name, $value)
	{
		$this->assign_vars[$name] = $value;
	}

	public function
	set($params, $value)
	{
		$this->$params = $value;
	}

	public function
	cache($Cache)
	{
		$this->Cache = $Cache;
	}
}