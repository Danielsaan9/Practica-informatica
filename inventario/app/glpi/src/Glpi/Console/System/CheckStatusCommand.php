<?php

namespace Glpi\Console\System;

use Glpi\Console\AbstractCommand;
use Glpi\System\Status\StatusChecker;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use function Safe\json_encode;

class CheckStatusCommand extends AbstractCommand
{
    protected bool $requires_db = false;

    protected function configure()
    {
        parent::configure();

        $this->setName('system:status');
        $this->setDescription(__('Check system status'));
        $this->addOption(
            'private',
            'p',
            InputOption::VALUE_NONE,
            'Status information publicity. Private status information may contain potentially sensitive information such as version information.'
        );
        $this->addOption(
            'service',
            's',
            InputOption::VALUE_OPTIONAL,
            'The service to check or all',
            'all'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $status = StatusChecker::getServiceStatus($input->getOption('service'), !$input->getOption('private'));
        $output->writeln(json_encode($status, JSON_PRETTY_PRINT));

        return 0; // Success
    }
}
