<?php

use Jerve\Db\Mysql;
require_once("vendor/autoload.php");


/*
 * init Jerve.
 */
/*
$jerve = new Jerve\Jerve([
		"app_dir" => "App",
		"view_dir" => "App/View",
		"router" => $router,
		"db" => $db
	]);
*/

Mysql::set_conf([
    "db" => "",
    "server" => "",
    "user" => "",
    "password" => "",
    "long_connect" => false,
    "log" => true
]);
try {
    $jerve = new Jerve\Jerve(dirname(__FILE__), 'Apps');
    $jerve->router('home', 'Index/Index');
    $jerve->run();
} catch (\Exception $e) {
    echo $e->getMessage();
}
