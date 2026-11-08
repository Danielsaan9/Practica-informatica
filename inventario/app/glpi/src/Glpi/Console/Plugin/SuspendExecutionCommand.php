<?php

namespace Glpi\Console\Plugin;

use Glpi\Console\AbstractCommand;
use Plugin;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SuspendExecutionCommand extends AbstractCommand
{
    protected function configure()
    {
        parent::configure();

        $this->setName('plugin:suspend_execution');
        $this->setDescription(__('Suspend execution of all active plugins'));
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (!(new Plugin())->suspendAllPluginsExecution()) {
            $this->output->writeln(
                '<error>' . __('An unexpected error occurred') . '</error>',
                OutputInterface::VERBOSITY_QUIET
            );
            return self::FAILURE;
        }

        $output->writeln('<info>' . __('Execution of all active plugins has been suspended.') . '</info>');

        return self::SUCCESS;
    }
}
