<?php

namespace Glpi\Asset\Capacity;

use CommonGLPI;
use Domain;
use Domain_Item;
use Glpi\Asset\CapacityConfig;
use Override;
use Session;

class HasDomainsCapacity extends AbstractCapacity
{
    public function getLabel(): string
    {
        return Domain::getTypeName(Session::getPluralNumber());
    }

    public function getIcon(): string
    {
        return Domain::getIcon();
    }

    #[Override]
    public function getDescription(): string
    {
        return __("Track domains, records and their expiration dates");
    }

    public function getCloneRelations(): array
    {
        return [
            Domain_Item::class,
        ];
    }

    public function isUsed(string $classname): bool
    {
        return parent::isUsed($classname)
            && $this->countAssetsLinkedToPeerItem($classname, Domain_Item::class) > 0;
    }

    public function getCapacityUsageDescription(string $classname): string
    {
        return sprintf(
            __('%1$s domains attached to %2$s assets'),
            $this->countPeerItemsUsage($classname, Domain_Item::class),
            $this->countAssetsLinkedToPeerItem($classname, Domain_Item::class)
        );
    }

    public function onClassBootstrap(string $classname, CapacityConfig $config): void
    {
        $this->registerToTypeConfig('domain_types', $classname);

        CommonGLPI::registerStandardTab(
            $classname,
            Domain_Item::class,
            65,
        );
    }

    public function onCapacityDisabled(string $classname, CapacityConfig $config): void
    {
        // Unregister from domain types
        $this->unregisterFromTypeConfig('domain_types', $classname);

        //Delete related domains
        $domains = new Domain_Item();
        $domains->deleteByCriteria(
            [
                'itemtype' => $classname,
            ],
            force: true,
            history: false
        );

        // Clean history related to domains
        $this->deleteRelationLogs($classname, Domain::class);
        $this->deleteRelationLogs($classname, Domain_Item::class);

        // Clean display preferences
        $this->deleteDisplayPreferences($classname, $this->getSearchOptions($classname));
    }
}
