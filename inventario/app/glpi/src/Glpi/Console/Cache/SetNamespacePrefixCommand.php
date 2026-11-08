<?php

namespace Glpi\Console\Cache;

use Glpi\Cache\CacheManager;
use Glpi\Console\AbstractCommand;
use Glpi\Console\Command\ConfigurationCommandInterface;
use Glpi\Console\Exception\EarlyExitException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @since 10.0.0
 */
class SetNamespacePrefixCommand extends AbstractCommand implements ConfigurationCommandInterface
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

        $this->setName('cache:set_namespace_prefix');
        $this->setDescription('Define cache namespace prefix');

        $this->addArgument('prefix', InputArgument::REQUIRED, 'Namespace prefix');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $prefix = $input->getArgument('prefix');

        // Store configuration
        $success = $this->cache_manager->setNamespacePrefix($prefix);

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
