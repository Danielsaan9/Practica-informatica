<?php

namespace Glpi\Kernel\Listener\PostBootListener;

use Config;
use DBConnection;
use Glpi\Application\Environment;
use Glpi\Debug\Profiler;
use Glpi\Kernel\ListenersPriority;
use Glpi\Kernel\PostBootEvent;
use Plugin;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final readonly class CheckPluginsStates implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            PostBootEvent::class => ['onPostBoot', ListenersPriority::POST_BOOT_LISTENERS_PRIORITIES[self::class]],
        ];
    }

    public function onPostBoot(): void
    {
        global $DB;
        if (
            !DBConnection::isDbAvailable()
            || !Config::isLegacyConfigurationLoaded()
            || !$DB->tableExists(Plugin::getTable())
        ) {
            return;
        }

        Profiler::getInstance()->start('CheckPluginsStates::execute', Profiler::CATEGORY_BOOT);

        // Must be done before checking the plugins states, as the state check is responsible for loading
        // the plugin `setup.php` file, which declares the plugin hooks (`plugin_tester_boot()`, ...).
        if (Environment::get()->shouldSetupTesterPlugin()) {
            $this->setupTesterPlugin();
        }

        (new Plugin())->checkStates();

        Profiler::getInstance()->stop('CheckPluginsStates::execute');
    }

    private function setupTesterPlugin(): void
    {
        global $DB;
        $DB->updateOrInsert(table: Plugin::getTable(), params: [
            'directory' => 'tester',
            'name'      => 'tester',
            'state'     => 1,
            'version'   => '1.0.0',
        ], where: ['directory' => 'tester']);
    }
}
