<?php

namespace Glpi\Console\Database;

use Glpi\Console\AbstractCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Update;

final class IsUpToDateCommand extends AbstractCommand
{
    protected bool $requires_db_up_to_date = false;

    protected function configure()
    {
        parent::configure();

        $this->setName('database:is_up_to_date');
        $this->setAliases(['db:is_up_to_date']);
        $this->setDescription(__('Check if the database schema version matches the installed GLPI version.'));
    }

    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ): int {
        if (!Update::isDbUpToDate()) {
            $output->writeln(
                '<error>' . __('The database is not up to date.') . '</error>',
                OutputInterface::VERBOSITY_QUIET
            );
            return Command::FAILURE;
        }

        $output->writeln(__('The database is up to date.'));

        return Command::SUCCESS;
    }
}
