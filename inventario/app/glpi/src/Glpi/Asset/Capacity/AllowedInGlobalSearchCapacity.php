<?php

namespace Glpi\Asset\Capacity;

use Glpi\Asset\CapacityConfig;
use Override;

class AllowedInGlobalSearchCapacity extends AbstractCapacity
{
    public function getLabel(): string
    {
        return __('Global search');
    }

    public function getIcon(): string
    {
        return 'ti ti-search';
    }

    #[Override]
    public function getDescription(): string
    {
        return __("Include in global search (from the header bar) results");
    }

    public function isUsed(string $classname): bool
    {
        return false; // Prevent a warning on deactivation
    }

    public function getCapacityUsageDescription(string $classname): string
    {
        return '';
    }

    public function onClassBootstrap(string $classname, CapacityConfig $config): void
    {
        $this->registerToTypeConfig('globalsearch_types', $classname);
    }

    public function onCapacityDisabled(string $classname, CapacityConfig $config): void
    {
        $this->unregisterFromTypeConfig('globalsearch_types', $classname);
    }
}
