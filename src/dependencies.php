<?php

// DIC configuration

use Greenter\App\Controllers\HomeController;
use Greenter\App\Controllers\ReportController;
use Greenter\App\Controllers\SecurityController;
use Greenter\App\Middlewares\SessionMiddleware;
use Greenter\App\Repository\UserRepository;
use Greenter\Parser\DocumentParserInterface;
use Greenter\Report\HtmlReport;
use Greenter\Report\PdfReport;
use Greenter\Report\ReportInterface;
use Greenter\Report\XmlUtils;
use Greenter\Xml\Parser\InvoiceParser;
use Greenter\Xml\Parser\NoteParser;

$container = $app->getContainer();

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Katzgrau\KLogger\Logger($settings['path'], $settings['level'], ['extension' => 'log']);

    return $logger;
};

// view renderer
$container['view'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    $view = new \Slim\Views\Twig($settings['template_path'], [
        'cache' => $settings['cache_dir'],
    ]);
    // Instantiate and add Slim specific extension
    $basePath = rtrim(str_ireplace('index.php', '', $c['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new Slim\Views\TwigExtension($c['router'], $basePath));

    return $view;
};

$container['html_report'] = function ($c) {
    $settings = $c->get('settings')['report'];

    return new \Greenter\Report\HtmlReport('', ['cache' => $settings['cache_dir']]);
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
$container[DocumentParserInterface::class] = function ($c) {
    return new \Greenter\App\Services\XmlParser($c);
};

$container[HomeController::class] = function ($c) {
    return new HomeController(
        $c->get('view'),
        $c->get('user_repository'),
        $c->get('settings')['upload_dir']
    );
};
$container[SecurityController::class] = function ($c) {
    return new SecurityController(
        $c->get('view'),
        $c->get('user_service'),
        $c->get('router')
    );
};

$container[ReportInterface::class] = function ($c) {
    $settings = $c->get('settings');
    $html = new HtmlReport('', [
        'cache' => $settings['report']['cache_dir'],
        //'strict_variables' => true,
    ]);
    $html->setTemplate('invoice2.html.twig');
    $render = new PdfReport($html);
    $render->setOptions($settings['pdf']['options']);
    $render->setBinPath(($settings['pdf']['bin']));

    return $render;
};

$container['xmlutils'] = function () {
    return new XmlUtils();
};

$container[InvoiceParser::class] = function () {
    return new InvoiceParser();
};

$container[NoteParser::class] = function () {
    return new NoteParser();
};

$container[ReportController::class] = function ($c) {
    return new ReportController(
        $c->get('user_repository'),
        $c->get(DocumentParserInterface::class),
        $c->get(ReportInterface::class),
        $c->get('xmlutils'),
        $c->get('settings')['upload_dir']
    );
};

$container[SessionMiddleware::class] = function ($c) {
    return new SessionMiddleware($c);
};
