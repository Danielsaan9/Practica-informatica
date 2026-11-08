<?php

namespace Glpi\Console\Cache;

use Glpi\Cache\CacheManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use function Safe\json_encode;

class DebugCommand extends Command
{
    protected bool $requires_db_up_to_date = false;

    protected function configure()
    {
        parent::configure();

        $this->setName('cache:debug');
        $this->setDescription('Debug GLPI cache.');

        $this->addOption(
            'key',
            'k',
            InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
            __('Cache key to debug.')
        );

        $this->addOption(
            'context',
            'c',
            InputOption::VALUE_REQUIRED,
            __('Cache context to clear (i.e. \'core\' or \'plugin:plugin_name\').'),
            CacheManager::CONTEXT_CORE
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $cache_manager = new CacheManager();

        $keys = $input->getOption('key');
        $context = $input->getOption('context');
        if (!in_array($context, $cache_manager->getKnownContexts())) {
            throw new InvalidArgumentException(
                sprintf(__('Invalid cache context: "%s".'), $context)
            );
        }

        $cache_instance = $cache_manager->getCacheInstance($context);

        foreach ($keys as $key) {
            if (!$cache_instance->has($key)) {
                $output->writeln('<comment>' . sprintf(__('Cache key "%s" is not set.'), $key) . '</comment>');
            } else {
                $output->writeln('<comment>' . sprintf(__('Cache key "%s" value:'), $key) . '</comment>');
                $output->writeln(json_encode($cache_instance->get($key), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            }
        }

        return 0; // Success
    }
}
