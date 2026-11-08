<?php

namespace Glpi\Console\Migration;

use Glpi\Console\AbstractCommand;
use Glpi\Console\Command\ConfigurationCommandInterface;
use Glpi\Console\Exception\EarlyExitException;
use Glpi\Exception\Database\QueryException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MyIsamToInnoDbCommand extends AbstractCommand implements ConfigurationCommandInterface
{
    protected bool $requires_db_up_to_date = false;

    /**
     * Error code returned when failed to migrate one table.
     *
     * @var int
     */
    public const ERROR_TABLE_MIGRATION_FAILED = 1;

    /**
     * Error code returned if DB configuration file cannot be updated.
     *
     * @var int
     */
    public const ERROR_UNABLE_TO_UPDATE_CONFIG = 2;

    protected function configure()
    {
        parent::configure();

        $this->setName('migration:myisam_to_innodb');
        $this->setDescription(__('Migrate MyISAM tables to InnoDB'));
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $myisam_tables = $this->getDb()->getMyIsamTables();

        $output->writeln(
            sprintf(
                '<info>' . __('Found %s table(s) using MyISAM engine.') . '</info>',
                $myisam_tables->count()
            )
        );

        $errors = false;

        if (0 === $myisam_tables->count()) {
            $output->writeln('<info>' . __('No migration needed.') . '</info>');
        } else {
            $this->warnAboutExecutionTime();
            $this->askForConfirmation();

            $tables = [];
            foreach ($myisam_tables as $table_data) {
                $tables[] = $table_data['TABLE_NAME'];
            }
            sort($tables);

            $progress_message = (fn(string $table) => sprintf(__('Migrating table "%s"...'), $table));

            foreach ($this->iterate($tables, $progress_message) as $table) {
                try {
                    $this->getDb()->doQuery(sprintf('ALTER TABLE %s ENGINE = InnoDB', $this->getDb()->quoteName($table)));
                } catch (QueryException $e) {
                    $message = sprintf(
                        __('Migration of table "%s" failed with message "(%s) %s".'),
                        $table,
                        $this->getDb()->errno(),
                        $this->getDb()->error()
                    );
                    $this->outputMessage(
                        '<error>' . $message . '</error>',
                        OutputInterface::VERBOSITY_QUIET
                    );
                    $errors = true;
                }
            }
        }

        if ($errors) {
            throw new EarlyExitException(
                '<error>' . __('Errors occurred during migration.') . '</error>',
                self::ERROR_TABLE_MIGRATION_FAILED
            );
        }

        if ($myisam_tables->count() > 0) {
            $output->writeln('<info>' . __('Migration done.') . '</info>');
        }

        return 0; // Success
    }

    public function getConfigurationFilesToUpdate(InputInterface $input): array
    {
        $config_files_to_update = ['config_db.php'];
        if (file_exists(GLPI_CONFIG_DIR . '/config_db_slave.php')) {
            $config_files_to_update[] = 'config_db_slave.php';
        }
        return $config_files_to_update;
    }
}
