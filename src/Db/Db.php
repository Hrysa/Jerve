<?php

namespace Jerve\Db;

class Db
{
    protected $conf;
    public $log;


    public function
    __construct()
    {

    }

    public function
    enable_log()
    {
        if(isset($this->conf['log']) && $this->conf['log'])
            return true;
        else
            return false;
    }
    
    public function
    set_log($sql, $params = "", $result)
    {
        $this->log[date('H:i:s')] = ["sql"=>$sql, "params"=>$params, "result"=>$result];
    }

    public function
    log()
    {
        return $this->log;
    }
}