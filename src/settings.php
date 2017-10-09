<?php
$curren_dir = __DIR__;
return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header

        // Renderer settings
        'renderer' => [
            'template_path' => $curren_dir . '/../templates/',
            'cache_dir' => $curren_dir . '/../logs',
        ],

        'database' => [
          'dsn' => 'sqlite:'. $curren_dir . '/../xmltopdf.sqlite',
          'user' => null,
          'pass' => null,
        ],

        'report' => [
            'template_path' => $curren_dir . '/app/Templates/',
            'cache_dir' => $curren_dir . '/../logs',
        ],

        'pdf' => [
            'bin' => $curren_dir . '/../wkhtmltopdf.exe',
            'options' => [
                'no-outline', // Make Chrome not complain
                //'viewport-size' => '1280x1024',
                //'page-width' => '21cm',
                //'page-height' => '29cm',
                'footer-html' => $curren_dir . '/app/Templates/footer.html',
            ]
        ],
        // Monolog settings
        'logger' => [
            'name' => 'slim-app',
            'path' => isset($_ENV['docker']) ? 'php://stdout' : $curren_dir . '/../logs/app.log',
            'level' => \Monolog\Logger::DEBUG,
        ],

        'upload_dir' => $curren_dir . '/../logs'
    ],
];
