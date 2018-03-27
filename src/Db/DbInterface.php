<?php

namespace Jerve\Db;

class DbInterface
{
    //protected $conf;
    public $log;


    protected function
    __construct()
    {

    }

//    public function
//    get_instance()
//    {
//        if(!self::$instace)
//            self::$instance = new DbInterface();
//
//        return self::$instance;
//    }

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

    static function
    echo()
    {

    }
}