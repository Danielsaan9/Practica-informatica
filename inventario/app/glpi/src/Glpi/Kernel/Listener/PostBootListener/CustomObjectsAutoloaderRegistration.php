<?php

namespace Glpi\Kernel\Listener\PostBootListener;

use Glpi\Asset\AssetDefinitionManager;
use Glpi\Debug\Profiler;
use Glpi\Dropdown\DropdownDefinitionManager;
use Glpi\Kernel\KernelListenerTrait;
use Glpi\Kernel\ListenersPriority;
use Glpi\Kernel\PostBootEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final readonly class CustomObjectsAutoloaderRegistration implements EventSubscriberInterface
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

        Profiler::getInstance()->start('CustomObjectsAutoloaderRegistration::execute', Profiler::CATEGORY_BOOT);

        AssetDefinitionManager::getInstance()->registerAutoload();
        DropdownDefinitionManager::getInstance()->registerAutoload();

        Profiler::getInstance()->stop('CustomObjectsAutoloaderRegistration::execute');
    }
}
