<?php

namespace Glpi\Asset\Capacity;

use CommonGLPI;
use Glpi\Asset\Asset_PeripheralAsset;
use Glpi\Asset\CapacityConfig;
use Override;
use Session;

class HasPeripheralAssetsCapacity extends AbstractCapacity
{
    public function getLabel(): string
    {
        return Asset_PeripheralAsset::getTypeName(Session::getPluralNumber());
    }

    public function getIcon(): string
    {
        return Asset_PeripheralAsset::getIcon();
    }

    #[Override]
    public function getDescription(): string
    {
        return __("Can be connected to external peripherals or monitors");
    }

    public function getCloneRelations(): array
    {
        return [
            Asset_PeripheralAsset::class,
        ];
    }

    public function getSearchOptions(string $classname): array
    {
        return Asset_PeripheralAsset::rawSearchOptionsToAdd();
    }

    private function countAssetsLinkedToPeripherals(string $asset_classname, string $relation_classname): int
    {
        return countDistinctElementsInTable(
            $relation_classname::getTable(),
            'items_id_asset',
            [
                'itemtype_asset' => $asset_classname,
            ]
        );
    }

    private function countPeripheralItemsUsage(string $asset_classname, string $relation_classname): int
    {
        global $CFG_GLPI;

        $count = 0;
        foreach ($CFG_GLPI['directconnect_types'] as $peripheral_itemtype) {
            $count += countDistinctElementsInTable(
                $relation_classname::getTable(),
                'items_id_peripheral',
                [
                    'itemtype_asset'      => $asset_classname,
                    'itemtype_peripheral' => $peripheral_itemtype,
                ]
            );
        }

        return $count;
    }

    public function isUsed(string $classname): bool
    {
        return parent::isUsed($classname)
            && $this->countAssetsLinkedToPeripherals($classname, Asset_PeripheralAsset::class) > 0;
    }

    public function getCapacityUsageDescription(string $classname): string
    {
        return sprintf(
            __('%1$s peripheral assets attached to %2$s assets'),
            $this->countPeripheralItemsUsage($classname, Asset_PeripheralAsset::class),
            $this->countAssetsLinkedToPeripherals($classname, Asset_PeripheralAsset::class)
        );
    }

    public function onClassBootstrap(string $classname, CapacityConfig $config): void
    {
        // Allow the asset to be linked to peripheral asset
        $this->registerToTypeConfig('peripheralhost_types', $classname);

        CommonGLPI::registerStandardTab($classname, Asset_PeripheralAsset::class, 55);
    }

    public function onCapacityDisabled(string $classname, CapacityConfig $config): void
    {
        // Unregister from peripheral hosts types
        $this->unregisterFromTypeConfig('peripheralhost_types', $classname);

        // Delete related items
        $relation = new Asset_PeripheralAsset();
        $relation->deleteByCriteria(['itemtype_asset' => $classname], force: true, history: false);

        // Clean history related items
        $this->deleteRelationLogs($classname, Asset_PeripheralAsset::class);

        // Clean display preferences
        $relation_search_options = Asset_PeripheralAsset::rawSearchOptionsToAdd();
        $this->deleteDisplayPreferences($classname, $relation_search_options);
    }
}
