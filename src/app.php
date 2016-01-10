<?php

require_once __DIR__ . "/../config.php";
require_once __DIR__ . '/../vendor/autoload.php';

use Psr7Middlewares\Middleware;

$app = new \Slim\App(new \App\Lib\AppContainer());

$app->add(Middleware::ResponseTime());
$app->add(Middleware::TrailingSlash());

require_once 'routes.php';

$app->run();
