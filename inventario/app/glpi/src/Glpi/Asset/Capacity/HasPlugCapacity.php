<?php

namespace Glpi\Asset\Capacity;

use CommonGLPI;
use Glpi\Asset\CapacityConfig;
use Override;
use Plug;
use Session;

class HasPlugCapacity extends AbstractCapacity
{
    public function getLabel(): string
    {
        return Plug::getTypeName(Session::getPluralNumber());
    }

    public function getIcon(): string
    {
        return Plug::getIcon();
    }

    #[Override]
    public function getDescription(): string
    {
        return __("Has power plugs. Usually related to PDU or UPS");
    }

    public function getCloneRelations(): array
    {
        return [
            Plug::class,
        ];
    }

    public function isUsed(string $classname): bool
    {
        return parent::isUsed($classname)
            && $this->countAssetsHostingPlugs($classname, Plug::class) > 0;
    }

    private function countAssetsHostingPlugs(string $asset_classname, string $relation_classname): int
    {
        return countDistinctElementsInTable(
            $relation_classname::getTable(),
            'items_id_main',
            [
                'itemtype_main' => $asset_classname,
            ]
        );
    }

    public function getCapacityUsageDescription(string $classname): string
    {
        return sprintf(
            __('%1$s plugs declared on %2$s assets'),
            $this->countHostedPlugs($classname, Plug::class),
            $this->countAssetsHostingPlugs($classname, Plug::class)
        );
    }

    private function countHostedPlugs(string $asset_classname, string $relation_classname): int
    {
        return countElementsInTable(
            $relation_classname::getTable(),
            [
                'itemtype_main'      => $asset_classname,
            ]
        );
    }

    public function onClassBootstrap(string $classname, CapacityConfig $config): void
    {
        $this->registerToTypeConfig('plug_types', $classname);

        CommonGLPI::registerStandardTab($classname, Plug::class, 55);
    }

    public function onCapacityDisabled(string $classname, CapacityConfig $config): void
    {
        // Unregister from types
        $this->unregisterFromTypeConfig('plug_types', $classname);

        // Delete plugs hosted by the current type
        $plug = new Plug();
        $plug->deleteByCriteria(['itemtype_main' => $classname], force: true, history: false);

        // Clean history related to plugs
        $this->deleteRelationLogs($classname, Plug::class);
    }
}
