<?php

namespace Glpi\Console\System;

use Glpi\Console\AbstractCommand;
use Glpi\System\Status\StatusChecker;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use function Safe\json_encode;

class ListServicesCommand extends AbstractCommand
{
    protected function configure()
    {
        parent::configure();

        $this->setName('system:list_services');
        $this->setDescription(__('List system services'));
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $services = array_keys(StatusChecker::getServices());
        $output->writeln(json_encode($services, JSON_PRETTY_PRINT));

        return 0; // Success
    }
}
