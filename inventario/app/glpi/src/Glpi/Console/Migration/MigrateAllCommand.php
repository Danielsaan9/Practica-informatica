<?php

namespace Glpi\Console\Migration;

use Glpi\Console\AbstractCommand;
use Glpi\Console\Command\ConfigurationCommandInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MigrateAllCommand extends AbstractCommand implements ConfigurationCommandInterface
{
    protected function configure()
    {
        parent::configure();

        $this->setName('migration:migrate_all');
        $this->setDescription(__('Execute all recommended optional migrations.'));
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $commands = [
            'migration:myisam_to_innodb',
            'migration:dynamic_row_format',
            'migration:timestamps',
            'migration:utf8mb4',
            'migration:unsigned_keys',
        ];

        $options = [];
        foreach ($this->input->getOptions() as $option => $value) {
            if ($value === false || $value === null) {
                continue;
            }
            $options['--' . $option] = $value;
        }

        foreach ($commands as $name) {
            $this->output->writeln(
                '<comment>' . sprintf(__('Executing command "%s"...'), $name) . '</comment>',
            );
            $result = $this->getApplication()
                ->find($name)
                ->run(
                    new ArrayInput($options),
                    $this->output
                );

            if ($result !== self::SUCCESS) {
                return $result;
            }
        }

        return self::SUCCESS;
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
