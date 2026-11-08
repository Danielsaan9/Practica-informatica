<?php

namespace Glpi\Console\Cache;

use Glpi\Cache\CacheManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ClearCommand extends Command
{
    /**
     * Error code returned when failed to clear cache.
     *
     * @var int
     */
    public const ERROR_CACHE_CLEAR_FAILURE = 1;

    protected bool $requires_db_up_to_date = false;

    protected function configure()
    {
        parent::configure();

        $this->setName('cache:clear');
        $this->setDescription('Clear GLPI cache.');

        $this->addOption(
            'context',
            'c',
            InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
            __('Cache context to clear (i.e. \'core\' or \'plugin:plugin_name\'). All contexts are cleared by default.')
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $cache_manager = new CacheManager();

        $success = true;

        $contexts = $input->getOption('context');
        if (empty($contexts)) {
            $success = $cache_manager->resetAllCaches();
        } else {
            foreach ($contexts as $context) {
                if (!in_array($context, $cache_manager->getKnownContexts())) {
                    throw new InvalidArgumentException(
                        sprintf(__('Invalid cache context: "%s".'), $context)
                    );
                }
            }
            foreach ($contexts as $context) {
                $success = $cache_manager->getCacheInstance($context)->clear() && $success;
            }
        }

        if (!$success) {
            $output->writeln(
                '<error>' . __('Failed to clear cache.') . '</error>',
                OutputInterface::VERBOSITY_QUIET
            );
            return self::ERROR_CACHE_CLEAR_FAILURE;
        }

        $output->writeln('<info>' . __('Cache cleared successfully.') . '</info>');

        return 0; // Success
    }
}
