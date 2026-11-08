<?php

namespace Glpi\Kernel\Listener\PostBootListener;

use Glpi\Debug\Profile;
use Glpi\Debug\Profiler;
use Glpi\Kernel\ListenersPriority;
use Glpi\Kernel\PostBootEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final readonly class ProfilerStart implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            PostBootEvent::class => ['onPostBoot', ListenersPriority::POST_BOOT_LISTENERS_PRIORITIES[self::class]],
        ];
    }

    public function onPostBoot(): void
    {
        if (isCommandLine()) {
            Profiler::getInstance()->disable();
        } else {
            Profiler::getInstance()->start('php_request');

            // Start the debug profile
            Profile::getCurrent();
        }
    }
}
