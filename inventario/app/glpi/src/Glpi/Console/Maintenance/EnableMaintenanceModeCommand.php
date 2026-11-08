<?php

namespace Glpi\Console\Maintenance;

use Config;
use Glpi\Console\AbstractCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class EnableMaintenanceModeCommand extends AbstractCommand
{
    protected bool $requires_db_up_to_date = false;

    protected function configure()
    {
        parent::configure();

        $this->setName('maintenance:enable');
        $this->setDescription(__('Enable maintenance mode'));

        $this->addOption(
            'text',
            't',
            InputOption::VALUE_OPTIONAL,
            __('Text to display during maintenance')
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        global $CFG_GLPI;

        $values = [
            'maintenance_mode' => '1',
        ];
        if ($input->hasOption('text') && $input->getOption('text') !== null) {
            $values['maintenance_text'] = $input->getOption('text');
        }
        $config = new Config();
        $config->setConfigurationValues('core', $values);

        $message = sprintf(
            __('Maintenance mode activated. Backdoor using: %s'),
            $CFG_GLPI['url_base'] . '/index.php?skipMaintenance=1'
        );
        $output->writeln('<info>' . $message . '</info>');

        return 0; // Success
    }
}
