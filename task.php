<?php

use Config\Db;
use App\Router;
use App\Task;

include('config/Db.php');

require_once "./vendor/autoload.php";
header('Content-Type: application/json');

$conn =  Db::getConnection();
$task = new Task($conn);
$router = new Router($task);
$router->handleRequest();
