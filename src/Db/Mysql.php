<?php

namespace Jerve\Db;
use PDO;
use Exception;

class Mysql extends DbInterface
{
    static public $instance;

	static private $db;

	static private $server;

    static private $password;

    static private $user;

    static private $long_connect;

    static private $conf;

    // mysql connection.
    private $connect;

    // prepared handle.
    private $handle;

    private $result;

	public function
	__construct($conf = '')
	{
		parent::__construct();
		//$this->set_conf($conf);
	}

	static function get_instance() {
	    if(!self::$conf)
	        throw new Exception('config does not exists.');

        if(!self::$instance)
            self::$instance = new self();
        return self::$instance;
    }

	private function
	set_connect()
	{
	    $server = self::$server;
	    $db = self::$db;
	    $password = self::$password;
	    $user = self::$user;
        $long_connect = self::$long_connect;

		$this->connect = new PDO("mysql:host=$server;dbname=$db","$user","$password", array(
    		PDO::ATTR_PERSISTENT => $this->long_connect,
    		PDO::ATTR_EMULATE_PREPARES => false,
    		PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
		));

		if(!$this->connect)
		    throw new Exception("mysql driver: set_connect() ");
		$this->log['connect_time'] = date("Y-m-d H:i:s");
	}

	static public function
	set_conf($conf)
	{
		if( isset($conf['db']) && isset($conf['server']) && isset($conf['password']) && isset($conf['user']) ) {
			self::$db = $conf['db'];
            self::$server = $conf['server'];
            self::$password = $conf['password'];
            self::$user = $conf['user'];
            self::$long_connect = isset($conf['long_connect']) ? $conf['long_connect'] : true;
            self::$conf = $conf;
			return true;
		} else
			return false;
	}

	public function
	execute($sql, $params = NULL)
	{
		if(!$this->connect)
			$this->set_connect();

		$this->handle = $this->connect->prepare($sql);
		$this->query($params);
		return $this;
	}

	public function
    getlastInsertId()
    {
        return $this->connect->lastInsertId();
    }

	public function
    query($params = '')
    {
        $handle = $this->handle;
        // clean up query result.
        $this->result = NULL;
        if($handle) {
            if($r = $handle->execute($params)) {
                $result = json_decode(json_encode($handle->fetchAll(PDO::FETCH_OBJ)), 1);
                if($this->enable_log())
                    $this->set_log($sql, $params, $result);
                 $this->result = $result;
            }
        }
        return $this;
    }

	public function
    one($fields = '')
    {
        if($fields) {

            $fields = explode(',', $fields);
            $result = [];
            if (count($fields) > 1) {
                foreach ($fields as $each)
                    $result[$each] = $this->result[$each];
            } else
                $result = current($this->result)[$fields[0]];

        } else if(is_array($this->result)) {
            $result = $this->result[0];
        }
        else
            $result = $this->result;

        return $result;
    }

    public function
    all()
    {
        return $this->result;
    }
    
}