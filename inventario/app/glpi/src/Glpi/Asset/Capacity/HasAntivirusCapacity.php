<?php

namespace Glpi\Asset\Capacity;

use CommonGLPI;
use Glpi\Asset\CapacityConfig;
use ItemAntivirus;
use Override;
use Session;

class HasAntivirusCapacity extends AbstractCapacity
{
    public function getLabel(): string
    {
        return ItemAntivirus::getTypeName(Session::getPluralNumber());
    }

    public function getIcon(): string
    {
        return ItemAntivirus::getIcon();
    }

    #[Override]
    public function getDescription(): string
    {
        return __("List antivirus software");
    }

    public function getCloneRelations(): array
    {
        return [
            ItemAntivirus::class,
        ];
    }

    public function getSearchOptions(string $classname): array
    {
        return ItemAntivirus::rawSearchOptionsToAdd();
    }

    public function isUsed(string $classname): bool
    {
        return parent::isUsed($classname)
            && $this->countAssetsLinkedToPeerItem($classname, ItemAntivirus::class) > 0;
    }

    public function getCapacityUsageDescription(string $classname): string
    {
        return sprintf(
            __('%1$s antiviruses attached to %2$s assets'),
            $this->countPeerItemsUsage($classname, ItemAntivirus::class),
            $this->countAssetsLinkedToPeerItem($classname, ItemAntivirus::class)
        );
    }

    public function onClassBootstrap(string $classname, CapacityConfig $config): void
    {
        $this->registerToTypeConfig('itemantivirus_types', $classname);

        CommonGLPI::registerStandardTab($classname, ItemAntivirus::class, 55);
    }

    public function onCapacityDisabled(string $classname, CapacityConfig $config): void
    {
        // Unregister from types
        $this->unregisterFromTypeConfig('itemantivirus_types', $classname);

        //Delete related items
        $avs = new ItemAntivirus();
        $avs->deleteByCriteria(['itemtype' => $classname], force: true, history: false);

        // Clean history related items
        $this->deleteRelationLogs($classname, ItemAntivirus::class);

        // Clean display preferences
        $avs_search_options = ItemAntivirus::rawSearchOptionsToAdd();
        $this->deleteDisplayPreferences($classname, $avs_search_options);
    }
}
