<?php

namespace Glpi\Console\Database;

use DBConnection;
use Glpi\Console\AbstractCommand;
use Glpi\Console\Command\ConfigurationCommandInterface;
use Glpi\Console\Exception\EarlyExitException;
use Glpi\System\Requirement\DbTimezones;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class EnableTimezonesCommand extends AbstractCommand implements ConfigurationCommandInterface
{
    /**
     * Error code returned if DB configuration file cannot be updated.
     *
     * @var int
     */
    public const ERROR_UNABLE_TO_UPDATE_CONFIG = 1;

    /**
     * Error code returned if prerequisites are missing.
     *
     * @var int
     */
    public const ERROR_MISSING_PREREQUISITES = 2;

    /**
     * Error code returned if some tables are still using datetime field type.
     *
     * @var int
     */
    public const ERROR_TIMESTAMP_FIELDS_REQUIRED = 3;

    protected function configure()
    {
        parent::configure();

        $this->setName('database:enable_timezones');
        $this->setAliases(['db:enable_timezones']);
        $this->setDescription(__('Enable timezones usage.'));
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $timezones_requirement = new DbTimezones($this->getDb());

        if (!$timezones_requirement->isValidated()) {
            $message = '<error>' . __('Timezones usage cannot be activated due to following errors:') . '</error>';
            foreach ($timezones_requirement->getValidationMessages() as $validation_message) {
                $message .= PHP_EOL . ' - <error>' . $validation_message . '</error>';
            }
            throw new EarlyExitException(
                $message,
                self::ERROR_MISSING_PREREQUISITES
            );
        }

        if (($datetime_count = $this->getDb()->getTzIncompatibleTables()->count()) > 0) {
            $message = sprintf(__('%1$s columns are using the deprecated datetime storage field type.'), $datetime_count)
            . ' '
            . sprintf(__('Run the "%1$s" command to migrate them.'), 'php bin/console migration:timestamps');
            throw new EarlyExitException(
                '<error>' . $message . '</error>',
                self::ERROR_TIMESTAMP_FIELDS_REQUIRED
            );
        }

        if (!DBConnection::updateConfigProperty(DBConnection::PROPERTY_USE_TIMEZONES, true)) {
            throw new EarlyExitException(
                '<error>' . __('Unable to update DB configuration file.') . '</error>',
                self::ERROR_UNABLE_TO_UPDATE_CONFIG
            );
        }

        $output->writeln('<info>' . __('Timezone usage has been enabled.') . '</info>');

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
