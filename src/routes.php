<?php

use Greenter\App\Controllers\HomeController;
use Greenter\App\Controllers\ReportController;
use Greenter\App\Controllers\SecurityController;
use Greenter\App\Middlewares\SessionMiddleware;

// Routes
$app->map(['GET', 'POST'], '/login', SecurityController::class . ':login')
    ->setName('login');

$app->map(['GET', 'POST'], '/register', SecurityController::class . ':register')
    ->setName('register');

$app->group('/', function () {
    $homeClass = HomeController::class;
    $this->get('', $homeClass . ':index')->setName('index');
    $this->map(['GET', 'POST'], 'configuracion', $homeClass . ':setting')->setName('setting');
    $this->get('image', $homeClass . ':image')->setName('image');
    $this->post('report', ReportController::class.':index')->setName('report');
    $this->get('logout', SecurityController::class . ':logout')->setName('logout');
})->add(SessionMiddleware::class);

