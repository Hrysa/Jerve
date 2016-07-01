<?php

namespace Jerve\Db;
use PDO;

class Mysql extends Db
{
	private $db;

	private $server;

	private $password;

	private $user;

	private $connect;

	private $long_connect;

	public function
	__construct($conf)
	{
		parent::__construct();
		$this->set_conf($conf);
	}

	private function
	set_connect()
	{
		$this->connect = new PDO("mysql:host=$this->server;dbname=$this->db","$this->user","$this->password", array(
    		PDO::ATTR_PERSISTENT => $this->long_connect,
    		PDO::ATTR_EMULATE_PREPARES => false,
    		PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
		));
		$this->log['connect_time'] = date("Y-m-d H:i:s");
	}

	private function
	set_conf($conf)
	{
		if( isset($conf['db']) && isset($conf['server']) && isset($conf['password']) && isset($conf['user']) ) {
			$this->db = $conf['db'];
			$this->server = $conf['server'];
			$this->password = $conf['password'];
			$this->user = $conf['user'];
			$this->long_connect = isset($conf['long_connect']) ? $conf['long_connect'] : true;
			$this->conf = $conf;
			return true;
		} else
			return false;
	}

	public function
	execute($sql, $params = "")
	{
		if(!$this->connect)
			$this->set_connect();

		$handle = $this->connect->prepare($sql);
		if($handle) {
			if($handle->execute($params)) {
				$result = json_decode(json_encode($handle->fetchAll(PDO::FETCH_OBJ)), 1);
				if($this->enable_log())
					$this->set_log($sql, $params, $result);
				return  count($result) == 1 ?  $result[0] : $result;
			}
		}
		return false;
	}

}