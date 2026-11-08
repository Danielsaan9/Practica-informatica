<?php

namespace Glpi\Asset\Capacity;

use CommonGLPI;
use Glpi\Asset\CapacityConfig;
use Item_OperatingSystem;
use OperatingSystem;
use Override;

class HasOperatingSystemCapacity extends AbstractCapacity
{
    // #Override
    public function getLabel(): string
    {
        return OperatingSystem::getTypeName();
    }

    public function getIcon(): string
    {
        return OperatingSystem::getIcon();
    }

    #[Override]
    public function getDescription(): string
    {
        return __("Display operating system information");
    }

    public function isUsed(string $classname): bool
    {
        return parent::isUsed($classname)
            && $this->countAssetsLinkedToPeerItem($classname, Item_OperatingSystem::class) > 0;
    }

    public function getCapacityUsageDescription(string $classname): string
    {
        return sprintf(
            __('Used by %1$s of %2$s assets'),
            $this->countAssetsLinkedToPeerItem($classname, Item_OperatingSystem::class),
            $this->countAssets($classname)
        );
    }

    // #Override
    public function onClassBootstrap(string $classname, CapacityConfig $config): void
    {
        $this->registerToTypeConfig('operatingsystem_types', $classname);

        // Register the operating system tab into our item
        CommonGLPI::registerStandardTab(
            $classname,
            Item_OperatingSystem::class,
            10 // Tab should be somewhere near the top
        );
    }

    // #Override
    public function getSearchOptions(string $classname): array
    {
        return Item_OperatingSystem::rawSearchOptionsToAdd($classname);
    }

    public function getCloneRelations(): array
    {
        return [
            Item_OperatingSystem::class,
        ];
    }

    // #Override
    public function onCapacityDisabled(string $classname, CapacityConfig $config): void
    {
        // Unregister from operating system types
        $this->unregisterFromTypeConfig('operatingsystem_types', $classname);

        // Delete related operating system data
        $item_os = new Item_OperatingSystem();
        $item_os->deleteByCriteria(
            crit   : ['itemtype' => $classname],
            force  : true,
            history: false
        );

        // Clean history related to operating systems
        $this->deleteRelationLogs($classname, OperatingSystem::class);
        $this->deleteRelationLogs($classname, Item_OperatingSystem::class);

        // Clean display preferences
        $this->deleteDisplayPreferences(
            $classname,
            Item_OperatingSystem::rawSearchOptionsToAdd($classname)
        );
    }
}
