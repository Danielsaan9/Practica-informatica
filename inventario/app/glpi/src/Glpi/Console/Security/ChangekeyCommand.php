<?php

namespace Glpi\Console\Security;

use Glpi\Console\AbstractCommand;
use Glpi\Console\Command\ConfigurationCommandInterface;
use GLPIKey;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ChangekeyCommand extends AbstractCommand implements ConfigurationCommandInterface
{
    /**
     * Error code returned when unable to renew key.
     *
     * @var int
     */
    public const ERROR_UNABLE_TO_RENEW_KEY = 1;

    protected function configure()
    {
        parent::configure();

        $this->setName('security:change_key');
        $this->setDescription(__('Change password storage key and update values in database.'));
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $glpikey = new GLPIKey();

        $fields = $glpikey->getFields();
        $configs = $glpikey->getConfigs();
        $conf_count = 0;
        foreach ($configs as $config) {
            $conf_count += count($config);
        }

        $output->writeln(
            sprintf(
                '<info>' . __('Found %1$s field(s) and %2$s configuration entries requiring migration.') . '</info>',
                count($fields),
                $conf_count
            )
        );

        $this->askForConfirmation();

        $created = $glpikey->generate();
        if (!$created) {
            $output->writeln(
                '<error>' . __('Unable to change security key!') . '</error>',
                OutputInterface::VERBOSITY_QUIET
            );
            return self::ERROR_UNABLE_TO_RENEW_KEY;
        }

        $this->output->write(PHP_EOL);

        $output->writeln('<info>' . __('New security key generated; database updated.') . '</info>');

        return 0; // Success
    }

    public function getConfigurationFilesToUpdate(InputInterface $input): array
    {
        return ['glpicrypt.key'];
    }
}
