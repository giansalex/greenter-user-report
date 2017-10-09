<?php
// DIC configuration

use Greenter\App\Controllers\HomeController;
use Greenter\App\Controllers\ReportController;
use Greenter\App\Controllers\SecurityController;
use Greenter\App\Middlewares\SessionMiddleware;
use Greenter\App\Repository\UserRepository;

$container = $app->getContainer();

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

// view renderer
$container['view'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    $view = new \Slim\Views\Twig($settings['template_path'], [
        'cache' => $settings['cache_dir'],
    ]);
    // Instantiate and add Slim specific extension
    $basePath = rtrim(str_ireplace('index.php','', $c['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new Slim\Views\TwigExtension($c['router'], $basePath));

    return $view;
};

$container['html_report'] = function ($c) {
    $settings = $c->get('settings')['report'];
    return new \Greenter\Report\HtmlReport($settings['template_path'], ['cache' => $settings['cache_dir']]);
};

// Repositories
$container['user_repository'] = function ($c) {
    $db = $c->get('settings')['database'];

    return new UserRepository($db['dsn'], $db['user'], $db['pass']);
};

// Services
$container['user_service'] = function ($c) {
    return new \Greenter\App\Services\UserService($c->get('user_repository'));
};
$container['report_service'] = function ($c) {
    return new \Greenter\App\Services\ReportService($c->get('html_report'));
};
$container['pdf_service'] = function ($c) {
    $settings = $c->get('settings')['pdf'];
    return new \Greenter\App\Services\PdfService($settings['bin'], $settings['options']);
};

$container[HomeController::class] = function($c) {
    return new HomeController(
        $c->get("view"),
        $c->get("user_repository"),
        $c->get('settings')['upload_dir']);
};
$container[SecurityController::class] = function($c) {
    return new SecurityController(
        $c->get("view"),
        $c->get("user_service"),
        $c->get("router"));
};
$container[ReportController::class] = function($c) {
    return new ReportController(
        $c->get("user_repository"),
        $c->get("report_service"),
        $c->get("pdf_service"),
        $c->get('settings')['upload_dir']);
};

$container[SessionMiddleware::class] = function($c) {
    $router = $c->get("router");
    $view = $c->get("view");
    return new SessionMiddleware($router, $view->getEnvironment());
};