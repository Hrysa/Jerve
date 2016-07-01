<?php
/*
 * Jerve library core file.
 *
 */
namespace Jerve;
 
class Jerve
{
	private $router;
	private $conf;

	public function __construct($conf)
	{
		$this->conf = $conf;
		$this->conf_global();
		$this->router = $conf['router'] ? $conf['router'] : new Router();
		$this->router->set_app_path($conf['app_dir']);

	}

	private function
	conf_global()
	{
		$GLOBALS['_JERVE_C'] = $this->conf;
	}

	public function
	get_conf()
	{
		return $this->conf;
	}

	public function
	run()
	{
		$this->router->dispatch();
	}
}