<?php

namespace Glpi\Kernel\Listener\PostBootListener;

use Glpi\Cache\CacheManager;
use Glpi\Debug\Profiler;
use Glpi\Kernel\ListenersPriority;
use Glpi\Kernel\PostBootEvent;
use Psr\SimpleCache\CacheInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final readonly class InitializeCache implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            PostBootEvent::class => ['onPostBoot', ListenersPriority::POST_BOOT_LISTENERS_PRIORITIES[self::class]],
        ];
    }

    public function onPostBoot(): void
    {
        /** @var CacheInterface|null $GLPI_CACHE */
        global $GLPI_CACHE;

        Profiler::getInstance()->start('InitializeCache::execute', Profiler::CATEGORY_BOOT);

        $cache_manager = new CacheManager();
        $GLPI_CACHE = $cache_manager->getCoreCacheInstance();

        Profiler::getInstance()->stop('InitializeCache::execute');
    }
}
