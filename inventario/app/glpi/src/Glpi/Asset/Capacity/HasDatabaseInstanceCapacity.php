<?php

namespace Glpi\Asset\Capacity;

use CommonGLPI;
use Database;
use DatabaseInstance;
use Glpi\Asset\CapacityConfig;
use Override;
use Session;

class HasDatabaseInstanceCapacity extends AbstractCapacity
{
    public function getLabel(): string
    {
        return DatabaseInstance::getTypeName(Session::getPluralNumber());
    }

    public function getIcon(): string
    {
        return Database::getIcon();
    }

    #[Override]
    public function getDescription(): string
    {
        return __("List database instances found by automatic inventory");
    }

    public function getCloneRelations(): array
    {
        return [
            // FIXME DatabaseInstance must be a CommonDBChild to be clonable
            // DatabaseInstance::class,
        ];
    }

    public function isUsed(string $classname): bool
    {
        return parent::isUsed($classname)
            && $this->countAssetsLinkedToPeerItem($classname, DatabaseInstance::class) > 0;
    }

    public function getCapacityUsageDescription(string $classname): string
    {
        return sprintf(
            __('%1$s database instances attached to %2$s assets'),
            $this->countPeerItemsUsage($classname, DatabaseInstance::class),
            $this->countAssetsLinkedToPeerItem($classname, DatabaseInstance::class)
        );
    }

    public function onClassBootstrap(string $classname, CapacityConfig $config): void
    {
        $this->registerToTypeConfig('databaseinstance_types', $classname);

        CommonGLPI::registerStandardTab($classname, DatabaseInstance::class, 150);
    }

    public function onCapacityDisabled(string $classname, CapacityConfig $config): void
    {
        global $DB;

        $this->unregisterFromTypeConfig('databaseinstance_types', $classname);

        // Unset itemtype and items_id fields for each database instance that is currently linked to an asset of this definition
        $DB->update(
            DatabaseInstance::getTable(),
            [
                'itemtype' => '',
                'items_id' => 0,
            ],
            [
                'itemtype' => $classname,
            ]
        );

        $this->deleteRelationLogs($classname, DatabaseInstance::class);
    }
}
