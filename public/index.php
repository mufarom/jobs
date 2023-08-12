<?php
session_start();
require '../autoload.php';

$routes = new \Job\Routes();

$entryPoint = new \CSY2028\EntryPoint($routes);

$entryPoint->run();