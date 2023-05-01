<?php

use Slim\Factory\AppFactory;

require_once __DIR__ . "/../vendor/autoload.php";

$container = require_once __DIR__ . "/container.php";

AppFactory::setContainer($container);
$app = AppFactory::create();

/**
 * Display Errors PHP/Slim
 */
ini_set("display_erros", 1);
$app->addErrorMiddleware(true, false, false);