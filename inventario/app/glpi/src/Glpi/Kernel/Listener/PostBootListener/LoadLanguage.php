<?php

namespace Glpi\Kernel\Listener\PostBootListener;

use Glpi\Debug\Profiler;
use Glpi\Kernel\ListenersPriority;
use Glpi\Kernel\PostBootEvent;
use Session;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final readonly class LoadLanguage implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            PostBootEvent::class => ['onPostBoot', ListenersPriority::POST_BOOT_LISTENERS_PRIORITIES[self::class]],
        ];
    }

    public function onPostBoot(): void
    {
        Profiler::getInstance()->start('LoadLanguage::execute', Profiler::CATEGORY_BOOT);

        Session::loadLanguage();

        Profiler::getInstance()->stop('LoadLanguage::execute');
    }
}
