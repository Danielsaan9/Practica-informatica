<?php

namespace Glpi\Console\Migration;

use Glpi\Console\AbstractCommand;
use Glpi\Console\Traits\PluginMigrationTrait;
use Glpi\Migration\AbstractPluginMigration;
use Glpi\Progress\ConsoleProgressIndicator;
use LogicException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Abstract command for plugin migrations.
 * Concrete classes just need to define their name, description and migration class.
 */
abstract class AbstractPluginMigrationCommand extends AbstractCommand
{
    use PluginMigrationTrait;

    /**
     * Returns an instance of the migration to use.
     *
     * @return AbstractPluginMigration
     */
    abstract public function getMigration(): AbstractPluginMigration;

    protected function configure()
    {
        $this->setName($this->getName());
        $this->setDescription($this->getDescription());

        $this->addOption(
            'dry-run',
            null,
            InputOption::VALUE_NONE,
            __('Simulate the migration')
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        global $PHPLOGGER;

        if (!$output instanceof ConsoleOutputInterface) {
            throw new LogicException('This command accepts only an instance of "ConsoleOutputInterface".');
        }

        $migration = $this->getMigration();
        $migration->setLogger($PHPLOGGER);
        $migration->setProgressIndicator(new ConsoleProgressIndicator($output));
        $result = $migration->execute((bool) $input->getOption('dry-run'));

        $this->outputPluginMigrationResult($output, $result);

        return $result->isFullyProcessed() && !$result->hasErrors() ? Command::SUCCESS : Command::FAILURE;
    }
}
