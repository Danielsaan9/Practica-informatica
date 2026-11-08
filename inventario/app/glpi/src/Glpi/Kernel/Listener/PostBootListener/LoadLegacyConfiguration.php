<?php

namespace Glpi\Kernel\Listener\PostBootListener;

use Config;
use Glpi\Debug\Profiler;
use Glpi\Kernel\ListenersPriority;
use Glpi\Kernel\PostBootEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final readonly class LoadLegacyConfiguration implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            PostBootEvent::class => ['onPostBoot', ListenersPriority::POST_BOOT_LISTENERS_PRIORITIES[self::class]],
        ];
    }

    public function onPostBoot(): void
    {
        global $CFG_GLPI;

        Profiler::getInstance()->start('LoadLegacyConfiguration::execute', Profiler::CATEGORY_BOOT);

        Config::loadLegacyConfiguration();

        Profiler::getInstance()->stop('LoadLegacyConfiguration::execute');
    }
}
