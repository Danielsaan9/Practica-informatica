<?php

namespace Glpi\Kernel\Listener\PostBootListener;

use Glpi\Debug\Profiler;
use Glpi\Kernel\KernelListenerTrait;
use Glpi\Kernel\ListenersPriority;
use Glpi\Kernel\PostBootEvent;
use Plugin;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final readonly class BootPlugins implements EventSubscriberInterface
{
    use KernelListenerTrait;

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
            !$this->isDatabaseUsable()
            || !$DB->tableExists(Plugin::getTable())
        ) {
            return;
        }

        Profiler::getInstance()->start('BootPlugins::execute', Profiler::CATEGORY_BOOT);

        $plugin = new Plugin();

        if (!$plugin->isPluginsExecutionSuspended()) {
            $plugin->bootPlugins();
        }

        Profiler::getInstance()->stop('BootPlugins::execute');
    }
}
