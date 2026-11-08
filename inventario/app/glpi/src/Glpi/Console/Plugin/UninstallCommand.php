<?php

namespace Glpi\Console\Plugin;

use Plugin;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UninstallCommand extends AbstractPluginCommand
{
    protected function configure()
    {
        parent::configure();

        $this->setName('plugin:uninstall');
        $this->setDescription('Run plugin(s) uninstallation script');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln(
            '<error>' . __("This action will permanently delete data") . '</error>',
            OutputInterface::VERBOSITY_NORMAL
        );
        $this->askForConfirmation();

        // Handle special inputs like directory=*
        $this->normalizeInput($input);

        // Read input
        $directories = $input->getArgument('directory');

        // Default command status: success until at least one plugin failed
        $failed = false;

        foreach ($directories as $directory) {
            $output->writeln(
                '<info>' . sprintf(__('Processing plugin "%s"...'), $directory) . '</info>',
                OutputInterface::VERBOSITY_NORMAL
            );

            $plugin = new Plugin();
            $plugin->checkPluginState($directory); // Be sure that plugin information are up to date in DB

            if (!$this->canRunUninstallMethod($directory)) {
                $failed = true;
                continue;
            }

            if (!$plugin->getFromDBByCrit(['directory' => $directory])) {
                $this->output->writeln(
                    '<error>' . sprintf(__('Unable to load plugin "%s" information.'), $directory) . '</error>',
                    OutputInterface::VERBOSITY_QUIET
                );
                $failed = true;
                continue;
            }
            $plugin->uninstall($plugin->fields['id']);

            // Check state after uninstallation
            if ($plugin->fields['state'] != Plugin::NOTINSTALLED) {
                $this->output->writeln(
                    '<error>' . sprintf(__('Plugin "%s" uninstallation failed.'), $directory) . '</error>',
                    OutputInterface::VERBOSITY_QUIET
                );
                $this->outputSessionBufferedMessages([WARNING, ERROR]);
                $failed = true;
                continue;
            }

            $message = __('Plugin "%1$s" has been uninstalled.');

            $output->writeln(
                '<info>' . sprintf($message, $directory) . '</info>',
                OutputInterface::VERBOSITY_NORMAL
            );
        }

        return $failed ? Command::FAILURE : Command::SUCCESS;
    }

    protected function getDirectoryChoiceQuestion()
    {
        return __('Which plugin(s) do you want to uninstall (comma separated values)?');
    }

    protected function getDirectoryChoiceChoices()
    {
        $choices = [];
        $plugin_iterator = $this->getDb()->request(
            [
                'FROM'  => Plugin::getTable(),
                'WHERE' => [
                    ['NOT' => ['state' => Plugin::NOTINSTALLED]],
                ],
            ]
        );
        foreach ($plugin_iterator as $plugin) {
            $choices[$plugin['directory']] = $plugin['name'];
        }

        ksort($choices, SORT_STRING);

        return $choices;
    }

    /**
     * Check if uninstall method can be run for given plugin.
     *
     * @param string  $directory
     *
     * @return bool
     */
    private function canRunUninstallMethod($directory)
    {
        $plugin = new Plugin();

        // Check that directory is valid
        $informations = $plugin->getInformationsFromDirectory($directory);
        if (empty($informations)) {
            $this->output->writeln(
                '<error>' . sprintf(__('Invalid plugin directory "%s".'), $directory) . '</error>',
                OutputInterface::VERBOSITY_QUIET
            );
            return false;
        }

        // Check current plugin state
        $is_already_known = $plugin->getFromDBByCrit(['directory' => $directory]);
        if (!$is_already_known) {
            $this->output->writeln(
                '<error>' . sprintf(__('Plugin "%s" is not yet installed.'), $directory) . '</error>',
                OutputInterface::VERBOSITY_QUIET
            );
            return false;
        }

        // Check if plugin is not already uninstalled
        if ($plugin->fields['state'] == Plugin::NOTINSTALLED) {
            $message = sprintf(
                __('Plugin "%s" is not installed.'),
                $directory
            );
            $this->output->writeln(
                '<error>' . $message . '</error>',
                OutputInterface::VERBOSITY_QUIET
            );
            return false;
        }

        return true;
    }
}
