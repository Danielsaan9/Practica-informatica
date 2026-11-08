<?php

namespace Glpi\Asset\Capacity;

use CommonGLPI;
use Contract;
use Contract_Item;
use Glpi\Asset\CapacityConfig;
use Override;

class HasContractsCapacity extends AbstractCapacity
{
    // #Override
    public function getLabel(): string
    {
        return Contract::getTypeName();
    }

    public function getIcon(): string
    {
        return Contract::getIcon();
    }

    #[Override]
    public function getDescription(): string
    {
        return __("Link contracts to the assets for costs, renewal and supplier tracking");
    }

    public function getCloneRelations(): array
    {
        return [
            Contract_Item::class,
        ];
    }

    public function isUsed(string $classname): bool
    {
        return parent::isUsed($classname)
            && $this->countAssetsLinkedToPeerItem($classname, Contract_Item::class) > 0;
    }

    public function getCapacityUsageDescription(string $classname): string
    {
        return sprintf(
            __('%1$s contracts attached to %2$s assets'),
            $this->countPeerItemsUsage($classname, Contract_Item::class),
            $this->countAssetsLinkedToPeerItem($classname, Contract_Item::class)
        );
    }

    // #Override
    public function onClassBootstrap(string $classname, CapacityConfig $config): void
    {
        // Allow our item to be linked to contracts
        $this->registerToTypeConfig('contract_types', $classname);

        // Register the contracts tab into our item
        CommonGLPI::registerStandardTab(
            $classname,
            Contract_Item::class,
            20 // Tab shouldn't be too far from the top
        );
    }

    // #Override
    public function onCapacityDisabled(string $classname, CapacityConfig $config): void
    {
        // Unregister from contracts types
        $this->unregisterFromTypeConfig('contract_types', $classname);

        // Delete related contract data
        $contract_item = new Contract_Item();
        $contract_item->deleteByCriteria(
            crit   : ['itemtype' => $classname],
            force  : true,
            history: false
        );

        // Clean history related to contracts (both sides of the relation)
        $this->deleteRelationLogs($classname, Contract::class);

        // Clean display preferences
        $this->deleteDisplayPreferences(
            $classname,
            Contract::rawSearchOptionsToAdd()
        );
    }
}
