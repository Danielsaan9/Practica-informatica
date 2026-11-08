<?php

namespace Glpi\Asset\Capacity;

use Glpi\Asset\CapacityConfig;
use Item_Rack;
use Override;
use Rack;
use Session;

class IsRackableCapacity extends AbstractCapacity
{
    public function getLabel(): string
    {
        return Rack::getTypeName(Session::getPluralNumber());
    }

    public function getIcon(): string
    {
        return Rack::getIcon();
    }

    #[Override]
    public function getDescription(): string
    {
        return __("Can be inserted in a datacenter rack");
    }

    public function getSearchOptions(string $classname): array
    {
        return Rack::rawSearchOptionsToAdd($classname::getType());
    }

    public function isUsed(string $classname): bool
    {
        return parent::isUsed($classname)
            && $this->countAssetsLinkedToPeerItem($classname, Item_Rack::class) > 0;
    }

    public function getCapacityUsageDescription(string $classname): string
    {
        return sprintf(
            __('Used by %1$s of %2$s assets'),
            $this->countAssetsLinkedToPeerItem($classname, Item_Rack::class),
            $this->countAssets($classname)
        );
    }

    public function onClassBootstrap(string $classname, CapacityConfig $config): void
    {
        $this->registerToTypeConfig('rackable_types', $classname);
    }

    public function onCapacityDisabled(string $classname, CapacityConfig $config): void
    {
        $this->unregisterFromTypeConfig('rackable_types', $classname);

        $item_rack = new Item_Rack();
        $item_rack->deleteByCriteria(['itemtype' => $classname], force: true, history: false);

        $this->deleteRelationLogs($classname, Item_Rack::class);
        $this->deleteRelationLogs($classname, Rack::class);

        $search_opts = Rack::rawSearchOptionsToAdd($classname);
        $this->deleteDisplayPreferences($classname, $search_opts);
    }
}
