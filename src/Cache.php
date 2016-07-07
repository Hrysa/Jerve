<?php

namespace Jerve;
class Cache
{
	private $View;
	
	private $expire_time;

	private $cache_path;

	private $root_path;

	private $app_path;

	private $file_path;

	private $expired_file_path;

	private $period;

	private $uri_md5;

	public function
	__construct(&$View, $expire_time)
	{
		$_J = &$GLOBALS['_J'];
		$this->root_path = $_J['root_path'];
		$this->app_path = $_J['app_path'];

		$this->expire_time = $expire_time;
		$this->View = $View;

		$this->uri_md5 = md5($_SERVER['QUERY_STRING']);
		$this->period = intval(time() / $expire_time);

		$filename = $this->uri_md5 . $this->period;
		$expired_filename = $this->uri_md5 . ($this->period-1);

		$this->cache_path = $this->root_path . DIRECTORY_SEPARATOR . $this->app_path . DIRECTORY_SEPARATOR . "Cache";

		if(!is_dir($this->cache_path)) {
		    if(!mkdir($this->cache_path, 0777, true))
		    	exit("create Cache directory failed.");
		}

		$this->file_path = $this->cache_path . DIRECTORY_SEPARATOR . $filename;
		$this->expired_file_path = $this->cache_path . DIRECTORY_SEPARATOR . $expired_filename;

		if(file_exists($this->file_path)) {
			echo file_get_contents($this->file_path);
			exit;
		} else {
			$this->View->cache($this);
		}
	}

	public function
	cache($data)
	{
		$dir = scandir($this->cache_path);
		foreach($dir as $each) {
			if(strpos($each, $this->uri_md5) === 0)
				unlink($this->cache_path. DIRECTORY_SEPARATOR .$each);
		}
		$file = fopen($this->file_path, "w") or exit("Unable to create file.");
		fwrite($file, $data);
		fclose($file);
	}
}