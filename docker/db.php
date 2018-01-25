<?php

$pathDb = __DIR__.'/../xmltopdf.sqlite';
if (file_exists($pathDb)) {
    echo 'Database already exists';
    return;
}
$content = file_get_contents(__DIR__.'/../src/data/sqlite_schema.sql');
$querys = explode(';', $content);


$db = new \PDO( 'sqlite:'.$pathDb, null, null);

foreach ($querys as $query) {
    $result = $db->query($query);
    if ($db->errorCode() !== '00000') {
        var_dump($db->errorInfo());
        $result = null;
        $db = null;
        if (file_exists($pathDb)) {
            unlink($pathDb);
        }

        exit(-1);
    }
}

echo 'Database Created in '.$pathDb.PHP_EOL;