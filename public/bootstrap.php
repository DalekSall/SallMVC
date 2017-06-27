<?php

define('PUBLICDIR', __DIR__);
define('SYSTEMDIR', __DIR__ . '/../app/');
define('URL', "http://".$_SERVER['HTTP_HOST']);
header("Content-Type:text/html;charset=UTF-8");

require SYSTEMDIR . "core/Autoloader.php";
spl_autoload_register('Autoloader::load');

$router = new Router();

$session = new Session();
$session->init();

$router->parse($_SERVER['REQUEST_URI']);
try {
  $router->run();
} catch(AddressNotFoundException $e) {
  require "404.html";
}

