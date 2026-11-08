<?php

namespace Glpi\Kernel\Listener\PostBootListener;

use Glpi\Asset\AssetDefinitionManager;
use Glpi\Debug\Profiler;
use Glpi\Dropdown\DropdownDefinitionManager;
use Glpi\Kernel\KernelListenerTrait;
use Glpi\Kernel\ListenersPriority;
use Glpi\Kernel\PostBootEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final readonly class CustomObjectsBoot implements EventSubscriberInterface
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
        if (!$this->isDatabaseUsable()) {
            // Requires the database to be available.
            return;
        }

        Profiler::getInstance()->start('CustomObjectsBoot::execute', Profiler::CATEGORY_BOOT);

        AssetDefinitionManager::getInstance()->bootDefinitions();
        DropdownDefinitionManager::getInstance()->bootDefinitions();

        Profiler::getInstance()->stop('CustomObjectsBoot::execute');
    }
}
