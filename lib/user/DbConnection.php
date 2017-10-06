<?php

include_once __DIR__.'/../../config.php';
/**
 * Class DbConnection
 */
final class DbConnection
{
    /**
     * @return PDO
     */
    public static function createConnection()
    {
        return new PDO(PDO_DSN,PDO_USER,PDO_PASS);
    }
}