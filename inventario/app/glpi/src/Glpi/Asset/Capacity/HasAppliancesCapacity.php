<?php

namespace Glpi\Asset\Capacity;

use Appliance;
use Appliance_Item;
use CommonGLPI;
use Glpi\Asset\CapacityConfig;
use Override;
use Session;

class HasAppliancesCapacity extends AbstractCapacity
{
    public function getLabel(): string
    {
        return Appliance::getTypeName(Session::getPluralNumber());
    }

    public function getIcon(): string
    {
        return Appliance::getIcon();
    }

    #[Override]
    public function getDescription(): string
    {
        return __("Can be part of an appliance. An appliance is a virtual object that groups several assets");
    }

    public function getCloneRelations(): array
    {
        return [
            Appliance_Item::class,
        ];
    }

    public function isUsed(string $classname): bool
    {
        return parent::isUsed($classname)
            && $this->countAssetsLinkedToPeerItem($classname, Appliance_Item::class) > 0;
    }

    public function getCapacityUsageDescription(string $classname): string
    {
        return sprintf(
            __('%1$s appliances attached to %2$s assets'),
            $this->countPeerItemsUsage($classname, Appliance_Item::class),
            $this->countAssetsLinkedToPeerItem($classname, Appliance_Item::class)
        );
    }

    public function onClassBootstrap(string $classname, CapacityConfig $config): void
    {
        $this->registerToTypeConfig('appliance_types', $classname);
        CommonGLPI::registerStandardTab($classname, Appliance_Item::class, 85);
    }

    public function onCapacityDisabled(string $classname, CapacityConfig $config): void
    {
        $this->unregisterFromTypeConfig('appliance_types', $classname);

        $appliance_item = new Appliance_Item();
        $appliance_item->deleteByCriteria([
            'itemtype' => $classname,
        ], true, false);

        $this->deleteRelationLogs($classname, Appliance_Item::class);
        $this->deleteRelationLogs($classname, Appliance::class);
        $this->deleteDisplayPreferences($classname, Appliance::rawSearchOptionsToAdd($classname));
    }
}
