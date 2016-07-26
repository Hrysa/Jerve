<?php
namespace Jerve;

class Model
{
	private $Db;

	public function
	__construct()
	{
		$_J = &$GLOBALS['_J'];
		$this->Db = $_J['Db'];
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