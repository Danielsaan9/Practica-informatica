<?php

namespace Glpi\Console\Cache;

use Glpi\Cache\CacheManager;
use Glpi\Console\AbstractCommand;
use Glpi\Console\Command\ConfigurationCommandInterface;
use Glpi\Console\Exception\EarlyExitException;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

use function Safe\json_encode;

/**
 * @since 10.0.0
 */
class ConfigureCommand extends AbstractCommand implements ConfigurationCommandInterface
{
    /**
     * Error code returned if cache configuration file cannot be write.
     *
     * @var int
     */
    public const ERROR_UNABLE_TO_WRITE_CONFIG = 1;

    protected bool $requires_db = false;

    /**
     * Cache manager.
     */
    private CacheManager $cache_manager;

    public function __construct()
    {
        $this->cache_manager = new CacheManager();

        parent::__construct();
    }

    protected function configure()
    {

        $this->setName('cache:configure');
        $this->setDescription('Define cache configuration');

        $this->addOption(
            'context',
            null,
            InputOption::VALUE_REQUIRED,
            __('Cache context (i.e. \'core\' or \'plugin:plugin_name\')'),
            'core'
        );

        $this->addOption(
            'dsn',
            null,
            InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
            __('Cache system DSN')
        );

        $this->addOption(
            'use-default',
            null,
            InputOption::VALUE_NONE,
            __('Unset cache configuration to use default filesystem cache for given context')
        );

        $this->addOption(
            'skip-connection-checks',
            null,
            InputOption::VALUE_NONE,
            __('Skip connection checks')
        );

        $this->addUsage('--use-default');
        $this->addUsage('--dsn=memcached://cache1.glpi-project.org --dsn=memcached://cache2.glpi-project.org');
        $this->addUsage('--dsn=redis://redis.glpi-project.org:6379/glpi');

        $adapters = $this->cache_manager->getAvailableAdapters();
        $help_lines = [
            sprintf(
                __('Valid cache systems are: %s.'),
                '<comment>' . implode('</comment>, <comment>', $adapters) . '</comment>'
            ),
            '',
            sprintf(__('%s DSN format: %s'), $adapters[CacheManager::SCHEME_MEMCACHED], 'memcached://[user:pass@][ip|host|socket[:port]][?weight=int]'),
            sprintf(__('%s DSN format: %s'), $adapters[CacheManager::SCHEME_REDIS], 'redis://[pass@][ip|host|socket[:port]][/db-index]'),
            sprintf(__('%s DSN format: %s'), $adapters[CacheManager::SCHEME_REDISS], 'rediss://[pass@][ip|host|socket[:port]][/db-index]'),
            '',
            __('Cache namespace can be use to ensure either separation or sharing of multiple GLPI instances data on same cache system.'),
        ];
        $this->setHelp(implode("\n", $help_lines));
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $use_default = $input->getOption('use-default');
        $context     = $input->getOption('context');
        $dsn         = $input->getOption('dsn');

        if (!$this->cache_manager->isContextValid($context, true)) {
            throw new InvalidArgumentException(
                sprintf(__('Invalid cache context: "%s".'), $context)
            );
        }

        if (count($dsn) === 0 && !$use_default) {
            throw new InvalidArgumentException(
                __('Either --dsn or --use-default options have to be used.')
            );
        } elseif (count($dsn) > 0 && $use_default) {
            throw new InvalidArgumentException(
                __('--dsn and --use-default options cannot be used simultaneously.')
            );
        }

        if ($use_default) {
            // Reset configuration for given context.
            $success = $this->cache_manager->unsetConfiguration($context);
            if (!$success) {
                throw new EarlyExitException(
                    '<error>' . __('Unable to write cache configuration file.') . '</error>',
                    self::ERROR_UNABLE_TO_WRITE_CONFIG
                );
            }
            $output->writeln(
                '<info>' . __('Cache configuration saved successfully.') . '</info>',
                OutputInterface::VERBOSITY_NORMAL
            );
            return 0; // Success
        }

        // Transform $dsn into single string if only one value is passed
        if (count($dsn) === 1) {
            $dsn = reset($dsn);
        }

        if (!$this->cache_manager->isDsnValid($dsn)) {
            throw new InvalidArgumentException(
                sprintf(__('Invalid cache DSN: "%s".'), json_encode($dsn))
            );
        }

        // Check connection
        if (!$input->getOption('skip-connection-checks')) {
            try {
                $this->cache_manager->testConnection($dsn);
            } catch (Throwable $e) {
                $error_msg = sprintf(__('An error occurred during connection to cache system: "%s"'), $e->getMessage());
                throw new EarlyExitException(
                    '<error>' . $error_msg . '</error>',
                    self::ERROR_UNABLE_TO_WRITE_CONFIG,
                    $e
                );
            }
        }

        // Store configuration
        $success = $this->cache_manager->setConfiguration($context, $dsn, []);

        if (!$success) {
            throw new EarlyExitException(
                '<error>' . __('Unable to write cache configuration file.') . '</error>',
                self::ERROR_UNABLE_TO_WRITE_CONFIG
            );
        }

        $output->writeln(
            '<info>' . __('Cache configuration saved successfully.') . '</info>',
            OutputInterface::VERBOSITY_NORMAL
        );

        return 0; // Success
    }

    public function getConfigurationFilesToUpdate(InputInterface $input): array
    {
        return [$this->cache_manager::CONFIG_FILENAME];
    }
}
