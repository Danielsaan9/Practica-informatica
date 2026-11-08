<?php

namespace Glpi\Asset\Capacity;

use CommonGLPI;
use Glpi\Asset\CapacityConfig;
use NetworkPort;
use Override;
use Session;

class HasNetworkPortCapacity extends AbstractCapacity
{
    public function getLabel(): string
    {
        return NetworkPort::getTypeName(Session::getPluralNumber());
    }

    public function getIcon(): string
    {
        return NetworkPort::getIcon();
    }

    public function getCloneRelations(): array
    {
        return [
            NetworkPort::class,
        ];
    }

    #[Override]
    public function getDescription(): string
    {
        return __("Has network ports (like ethernet and wlan)");
    }

    public function isUsed(string $classname): bool
    {
        return parent::isUsed($classname)
            && $this->countAssetsLinkedToPeerItem($classname, NetworkPort::class) > 0;
    }

    public function getCapacityUsageDescription(string $classname): string
    {
        return sprintf(
            __('%1$s network ports attached to %2$s assets'),
            $this->countPeerItemsUsage($classname, NetworkPort::class),
            $this->countAssetsLinkedToPeerItem($classname, NetworkPort::class)
        );
    }

    public function onClassBootstrap(string $classname, CapacityConfig $config): void
    {
        $this->registerToTypeConfig('networkport_types', $classname);

        CommonGLPI::registerStandardTab(
            $classname,
            NetworkPort::class,
            50
        );
    }

    public function onCapacityDisabled(string $classname, CapacityConfig $config): void
    {
        // Unregister from document types
        $this->unregisterFromTypeConfig('networkport_types', $classname);

        // Delete related networkport data
        $networkport = new NetworkPort();
        $networkport->deleteByCriteria(
            [
                'itemtype' => $classname,
            ],
            force: true,
            history: false
        );

        // Clean history related to networkports
        $this->deleteRelationLogs($classname, NetworkPort::class);

        // Clean display preferences
        $this->deleteDisplayPreferences($classname, NetworkPort::rawSearchOptionsToAdd($classname));
    }
}
