<?php

namespace Glpi\Log;

use Monolog\Handler\StreamHandler;

abstract class AbstractLogHandler extends StreamHandler
{
    public function __construct(string $logfile)
    {
        parent::__construct($logfile, GLPI_LOG_LVL);
    }
}
