<?php

namespace Glpi\Asset\Capacity;

use CommonGLPI;
use Glpi\Asset\CapacityConfig;
use Item_RemoteManagement;
use Override;
use Session;

class HasRemoteManagementCapacity extends AbstractCapacity
{
    public function getLabel(): string
    {
        return Item_RemoteManagement::getTypeName(Session::getPluralNumber());
    }

    public function getIcon(): string
    {
        return Item_RemoteManagement::getIcon();
    }

    #[Override]
    public function getDescription(): string
    {
        return __("Generate links for common remote access and control services");
    }

    public function getCloneRelations(): array
    {
        return [
            Item_RemoteManagement::class,
        ];
    }

    public function isUsed(string $classname): bool
    {
        return parent::isUsed($classname)
            && $this->countAssetsLinkedToPeerItem($classname, Item_RemoteManagement::class) > 0;
    }

    public function getCapacityUsageDescription(string $classname): string
    {
        return sprintf(
            __('%1$s remote management items attached to %2$s assets'),
            $this->countPeerItemsUsage($classname, Item_RemoteManagement::class),
            $this->countAssetsLinkedToPeerItem($classname, Item_RemoteManagement::class)
        );
    }

    public function onClassBootstrap(string $classname, CapacityConfig $config): void
    {
        $this->registerToTypeConfig('remote_management_types', $classname);

        CommonGLPI::registerStandardTab($classname, Item_RemoteManagement::class, 60);
    }

    public function getSearchOptions(string $classname): array
    {
        return Item_RemoteManagement::rawSearchOptionsToAdd($classname);
    }

    public function onCapacityDisabled(string $classname, CapacityConfig $config): void
    {
        $this->unregisterFromTypeConfig('remote_management_types', $classname);

        $remotemanagement_item = new Item_RemoteManagement();
        $remotemanagement_item->deleteByCriteria([
            'itemtype' => $classname,
        ], true, false);

        $this->deleteRelationLogs($classname, Item_RemoteManagement::class);
        $this->deleteDisplayPreferences($classname, Item_RemoteManagement::rawSearchOptionsToAdd($classname));
    }
}
