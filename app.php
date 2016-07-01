<?php

require_once("bootstrap.php");

/*
 * set router.
 */
$router = new Jerve\Router();
$router->set([
		['index','Index/index'],
		['home', 'Index/index'],
		['app', 'App/app'],
		['view', 'App/app']
	]);

/*
 * set database.
 */
$db = new Jerve\Db\Mysql([
		"db" => "renrenyou",
		"server" => "115.159.210.63:3306",
		"user" => "root",
		"password" => "shjjjr",
		"long_connect" => "true",
		"log" => true
	]);

/*
 * init Jerve.
 */
$jerve = new Jerve\Jerve([
		"app_dir" => "App",
		"view_dir" => "App/View",
		"router" => $router,
		"db" => $db
	]);

$jerve->run();