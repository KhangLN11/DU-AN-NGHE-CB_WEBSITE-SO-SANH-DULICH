<?php

session_start();

require_once __DIR__ . '/app/core/helpers.php';
require_once __DIR__ . '/app/core/Router.php';
require_once __DIR__ . '/app/core/Controller.php';
require_once __DIR__ . '/app/core/Model.php';

$router = new Router();

require_once __DIR__ . '/routes/web.php';

$router->dispatch();