<?php

namespace Glpi\Console\Database;

use Glpi\Console\Command\ConfigurationCommandInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConfigureCommand extends AbstractConfigureCommand implements ConfigurationCommandInterface
{
    protected function configure()
    {

        parent::configure();

        $this->setName('database:configure');
        $this->setAliases(['db:configure']);
        $this->setDescription('Define database configuration and writes it to the configuration file');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->configureDatabase($input, $output);

        return 0; // Success if configuration throw no EarlyExitException
    }

    public function getConfigurationFilesToUpdate(InputInterface $input): array
    {
        return ['config_db.php'];
    }
}
