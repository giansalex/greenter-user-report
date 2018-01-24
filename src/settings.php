<?php

$curren_dir = __DIR__;

return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header

        // Renderer settings
        'renderer' => [
            'template_path' => $curren_dir.'/../templates/',
            'cache_dir' => false,
        ],

        'database' => [
          'dsn' => 'sqlite:'.$curren_dir.'/../xmltopdf.sqlite',
          'user' => null,
          'pass' => null,
        ],

        'report' => [
            'template_path' => $curren_dir.'/app/Templates/',
            'cache_dir' => false,
        ],

        'pdf' => [
            'bin' => $curren_dir.'/../wkhtmltopdf.exe',
            'options' => [
                'no-outline', // Make Chrome not complain
                //'viewport-size' => '1280x1024',
                //'page-width' => '21cm',
                //'page-height' => '29cm',
                'footer-html' => $curren_dir.'/app/Templates/footer.html',
            ],
        ],
        // Monolog settings
        'logger' => [
            'name' => 'slim-app',
            'path' => __DIR__.'/../logs',
            'level' => Psr\Log\LogLevel::DEBUG,
        ],

        'upload_dir' => $curren_dir.'/../upload',
    ],
];
