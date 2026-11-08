<?php

namespace Glpi\Asset\Capacity;

use CommonGLPI;
use Glpi\Asset\CapacityConfig;
use ItemVirtualMachine;
use Override;
use Session;

class HasVirtualMachineCapacity extends AbstractCapacity
{
    public function getLabel(): string
    {
        return ItemVirtualMachine::getTypeName(Session::getPluralNumber());
    }

    public function getIcon(): string
    {
        return ItemVirtualMachine::getIcon();
    }

    #[Override]
    public function getDescription(): string
    {
        return __("List virtual machines attached to these assets");
    }

    public function getCloneRelations(): array
    {
        return [
            ItemVirtualMachine::class,
        ];
    }

    public function getSearchOptions(string $classname): array
    {
        return ItemVirtualMachine::rawSearchOptionsToAdd($classname);
    }

    public function isUsed(string $classname): bool
    {
        return parent::isUsed($classname)
            && $this->countAssetsLinkedToPeerItem($classname, ItemVirtualMachine::class) > 0;
    }

    public function getCapacityUsageDescription(string $classname): string
    {
        return sprintf(
            __('%1$s virtual machines attached to %2$s assets'),
            $this->countPeerItemsUsage($classname, ItemVirtualMachine::class),
            $this->countAssetsLinkedToPeerItem($classname, ItemVirtualMachine::class)
        );
    }

    public function onClassBootstrap(string $classname, CapacityConfig $config): void
    {
        $this->registerToTypeConfig('itemvirtualmachines_types', $classname);

        CommonGLPI::registerStandardTab($classname, ItemVirtualMachine::class, 55);
    }

    public function onCapacityDisabled(string $classname, CapacityConfig $config): void
    {
        // Unregister from types
        $this->unregisterFromTypeConfig('itemvirtualmachines_types', $classname);

        //Delete related items
        $avs = new ItemVirtualMachine();
        $avs->deleteByCriteria(['itemtype' => $classname], force: true, history: false);

        // Clean history related items
        $this->deleteRelationLogs($classname, ItemVirtualMachine::class);

        // Clean display preferences
        $avs_search_options = ItemVirtualMachine::rawSearchOptionsToAdd($classname);
        $this->deleteDisplayPreferences($classname, $avs_search_options);
    }
}
