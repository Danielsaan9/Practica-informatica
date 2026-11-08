<?php

namespace Glpi\Console\Command;

use Symfony\Component\Console\Input\InputInterface;

interface ConfigurationCommandInterface
{
    /**
     * Defines the list of configuration files that would be updated by the command.
     * Files path must be relative to `GLPI_CONFIG_DIR`.
     *
     * @param InputInterface $input
     *
     * @return string[]
     */
    public function getConfigurationFilesToUpdate(InputInterface $input): array;
}
