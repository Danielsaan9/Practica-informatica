<?php

namespace Glpi\Console\Plugin;

use Plugin;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DeactivateCommand extends AbstractPluginCommand
{
    /**
     * Error code returned when a plugin deactivation failed.
     *
     * @var int
     */
    public const ERROR_PLUGIN_DEACTIVATION_FAILED = 1;

    protected function configure()
    {
        parent::configure();

        $this->setName('plugin:deactivate');
        $this->setDescription('Deactivate plugin(s)');
        $this->setAliases(['plugin:disable']);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $this->normalizeInput($input);

        $directories   = $input->getArgument('directory');

        $failed = false;

        foreach ($directories as $directory) {
            $output->writeln(
                '<info>' . sprintf(__('Processing plugin "%s"...'), $directory) . '</info>',
                OutputInterface::VERBOSITY_NORMAL
            );

            $plugin = new Plugin();
            $plugin->checkPluginState($directory); // Be sure that plugin information are up to date in DB

            if (!$this->canRunDeactivateMethod($directory)) {
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

            if (!$plugin->unactivate($plugin->fields['id'])) {
                $this->output->writeln(
                    '<error>' . sprintf(__('Plugin "%s" deactivation failed.'), $directory) . '</error>',
                    OutputInterface::VERBOSITY_QUIET
                );
                $this->outputSessionBufferedMessages([WARNING, ERROR]);
                $failed = true;
                continue;
            }

            $output->writeln(
                '<info>' . sprintf(__('Plugin "%1$s" has been deactivated.'), $directory) . '</info>',
                OutputInterface::VERBOSITY_NORMAL
            );
        }

        if ($failed) {
            return self::ERROR_PLUGIN_DEACTIVATION_FAILED;
        }

        return 0; // Success
    }

    /**
     * Check if deactivate method can be run for given plugin.
     *
     * @param string  $directory
     *
     * @return bool
     */
    private function canRunDeactivateMethod($directory)
    {

        $plugin = new Plugin();

        // Check that directory is valid
        if (!$plugin->isLoadable($directory)) {
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

        if (Plugin::ACTIVATED != $plugin->fields['state']) {
            $this->output->writeln(
                '<info>' . sprintf(__('Plugin "%s" is already inactive.'), $directory) . '</info>',
                OutputInterface::VERBOSITY_NORMAL
            );
            return false;
        }

        return true;
    }

    protected function getDirectoryChoiceQuestion()
    {

        return __('Which plugin(s) do you want to deactivate (comma separated values)?');
    }

    protected function getDirectoryChoiceChoices()
    {

        $choices = [];
        $plugin_iterator = $this->getDb()->request(
            [
                'FROM'  => Plugin::getTable(),
                'WHERE' => [
                    'state' => Plugin::ACTIVATED,
                ],
            ]
        );
        foreach ($plugin_iterator as $plugin) {
            $choices[$plugin['directory']] = $plugin['name'];
        }

        ksort($choices, SORT_STRING);

        return $choices;
    }
}
