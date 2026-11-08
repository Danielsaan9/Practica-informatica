<?php

namespace Glpi\Kernel\Listener\PostBootListener;

use Glpi\Debug\Profiler;
use Glpi\DependencyInjection\PluginContainer;
use Glpi\Kernel\KernelListenerTrait;
use Glpi\Kernel\ListenersPriority;
use Glpi\Kernel\PostBootEvent;
use Plugin;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final readonly class InitializePlugins implements EventSubscriberInterface
{
    use KernelListenerTrait;

    public function __construct(private PluginContainer $pluginContainer) {}

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

        Profiler::getInstance()->start('InitializePlugins::execute', Profiler::CATEGORY_BOOT);

        $plugin = new Plugin();

        if (!$plugin->isPluginsExecutionSuspended()) {
            $plugin->init();
        }

        $this->pluginContainer->initializeContainer();

        Profiler::getInstance()->stop('InitializePlugins::execute');
    }
}
