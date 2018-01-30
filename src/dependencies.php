<?php

// DIC configuration

use Greenter\App\Controllers\HomeController;
use Greenter\App\Controllers\ReportController;
use Greenter\App\Controllers\SecurityController;
use Greenter\App\Middlewares\SessionMiddleware;
use Greenter\App\Repository\DbConnection;
use Greenter\App\Repository\UserRepository;
use Greenter\App\Services\PdoErrorLogger;
use Greenter\App\Services\SueReport;
use Greenter\Parser\DocumentParserInterface;
use Greenter\Report\PdfReport;
use Greenter\Report\ReportInterface;
use Greenter\Report\XmlUtils;
use Greenter\Xml\Parser\DespatchParser;
use Greenter\Xml\Parser\InvoiceParser;
use Greenter\Xml\Parser\NoteParser;
use Greenter\Xml\Parser\PerceptionParser;
use Greenter\Xml\Parser\RetentionParser;
use Greenter\Xml\Parser\SummaryParser;
use Greenter\Xml\Parser\VoidedParser;

$container = $app->getContainer();

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Katzgrau\KLogger\Logger($settings['path'], $settings['level'], ['extension' => 'log']);

    return $logger;
};

$container[PdoErrorLogger::class] = function ($c) {
    return new PdoErrorLogger($c->get('logger'));
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

// Report

$container['html_report'] = function ($c) {
    $settings = $c->get('settings')['report'];

    return new \Greenter\Report\HtmlReport('', ['cache' => $settings['cache_dir']]);
};

$container[SueReport::class] = function ($c) {
    return new SueReport($c->get('html_report'));
};

$container[ReportInterface::class] = function ($c) {
    $settings = $c->get('settings');

    $render = new PdfReport($c->get(SueReport::class));
    $render->setOptions($settings['pdf']['options']);
    $render->setBinPath(($settings['pdf']['bin']));

    return $render;
};

// Parsers
$container[InvoiceParser::class] = function () {
    return new InvoiceParser();
};

$container[NoteParser::class] = function () {
    return new NoteParser();
};

$container[SummaryParser::class] = function () {
    return new SummaryParser();
};

$container[VoidedParser::class] = function () {
    return new VoidedParser();
};

$container[DespatchParser::class] = function () {
    return new DespatchParser();
};

$container[RetentionParser::class] = function () {
    return new RetentionParser();
};

$container[PerceptionParser::class] = function () {
    return new PerceptionParser();
};

// xmlutils
$container['xmlutils'] = function () {
    return new XmlUtils();
};

// Repositories
$container[DbConnection::class] = function ($c) {
    $db = $c->get('settings')['database'];

    return new DbConnection($db['dsn'], $db['user'], $db['pass']);
};

$container['user_repository'] = function ($c) {
    return new UserRepository($c);
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
