<?php

declare(strict_types=1);

use Carloscrndn\HelveticBasket\Controllers\HomeController;
// use Carloscrndn\HelveticBasket\Controllers\RecetteController;
// use Carloscrndn\HelveticBasket\Controllers\UserController;
$app->get('/', [HomeController::class, 'home']);
$app->get('/login', [HomeController::class, 'login']);
$app->get('/match/{match:[0-9]+}', [HomeController::class, 'match']);

