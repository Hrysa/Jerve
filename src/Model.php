<?php
namespace Jerve;

use Jerve\Db\Mysql;

class Model
{
	protected $Db;
	static public $instance;

	public function
	__construct()
	{
        $this->Db = Mysql::get_instance();
	}

	static public function
    instance()
    {
        if(!self::$instance)
            self::$instance = new self();
        return self::$instance;
    }

	public function
	execute($sql, $params = NULL)
	{
		return $this->Db->execute($sql, $params);
	}

	public function
	unique($data)
	{
		return $data[0];
	}
}