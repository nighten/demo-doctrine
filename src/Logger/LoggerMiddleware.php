<?php

declare(strict_types=1);

namespace App\Logger;

use Doctrine\DBAL\Driver;
use Doctrine\DBAL\Driver\Middleware;
use Doctrine\DBAL\Driver\Middleware\AbstractDriverMiddleware;
use Doctrine\DBAL\Logging\Connection;
use Psr\Log\LoggerInterface;
use SensitiveParameter;

readonly class LoggerMiddleware implements Middleware
{
    public function __construct(
        private LoggerInterface $doctrineLogger,
    ) {
    }

    public function wrap(Driver $driver): Driver
    {
        $driver = new class ($driver) extends AbstractDriverMiddleware {
            private LoggerInterface $doctrineLogger;

            public function setDoctrineLogger(LoggerInterface $doctrineLogger): void
            {
                $this->doctrineLogger = $doctrineLogger;
            }

            /**
             * {@inheritDoc}
             */
            public function connect(
                #[SensitiveParameter]
                array $params,
            ): Driver\Connection {
                $connection = parent::connect($params);
                return new Connection($connection, $this->doctrineLogger);
            }
        };
        $driver->setDoctrineLogger($this->doctrineLogger);
        return $driver;
    }
}
