<?php

namespace Glpi\Console\Security;

use Glpi\Console\AbstractCommand;
use Glpi\Console\Command\ConfigurationCommandInterface;
use Glpi\OAuth\Server;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ChangeOAuthKeyCommand extends AbstractCommand implements ConfigurationCommandInterface
{
    protected function configure()
    {
        parent::configure();

        $this->setName('security:change_oauth_key');
        $this->setDescription(__('(Re)generate OAuth keys'));
        $this->setHelp(__('This command will regenerate the OAuth keys. All existing access tokens will be invalidated. This only generates missing keys unless the --force option is used.'));
        $this->addOption('force', 'f', InputOption::VALUE_NONE, __('Force the regeneration of OAuth keys even if they already exist.'));
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $force = $input->getOption('force');
        if (!$force && Server::checkKeys()) {
            $output->writeln('<comment>' . __('OAuth keys already exist. Use --force option to regenerate them.') . '</comment>', OutputInterface::VERBOSITY_QUIET);
            return self::SUCCESS;
        }

        $this->askForConfirmation();

        if (!Server::generateKeys($force)) {
            $output->writeln('<error>' . __('Unable to generate OAuth keys.') . '</error>', OutputInterface::VERBOSITY_QUIET);

            return self::FAILURE;
        }

        $this->output->write(PHP_EOL);
        $output->writeln('<info>' . __('OAuth keys have been successfully generated.') . '</info>');
        return self::SUCCESS;
    }

    public function getConfigurationFilesToUpdate(InputInterface $input): array
    {
        return ['oauth.pem', 'oauth.pub'];
    }
}
