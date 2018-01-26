<?php
/**
 * Created by PhpStorm.
 * User: Administrador
 * Date: 25/01/2018
 * Time: 08:02 PM.
 */

namespace Greenter\App\Services;

use Psr\Log\LoggerInterface;

/**
 * Class PdoErrorLogger.
 */
class PdoErrorLogger
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * PdoErrorLogger constructor.
     *
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param \PDO|\PDOStatement $statement
     */
    public function err($statement)
    {
        $this->logger
            ->error('PDO code:'.$statement->errorCode(), $statement->errorInfo());
    }
}
