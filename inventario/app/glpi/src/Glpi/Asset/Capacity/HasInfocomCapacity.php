<?php

namespace Glpi\Asset\Capacity;

use CommonGLPI;
use Glpi\Asset\CapacityConfig;
use Infocom;
use Override;

class HasInfocomCapacity extends AbstractCapacity
{
    public function getLabel(): string
    {
        return Infocom::getTypeName();
    }

    public function getIcon(): string
    {
        return Infocom::getIcon();
    }

    #[Override]
    public function getDescription(): string
    {
        return __("Manage and track assets lifecycle, financial, administrative and warranty information");
    }

    public function getCloneRelations(): array
    {
        return [
            Infocom::class,
        ];
    }

    public function isUsed(string $classname): bool
    {
        return parent::isUsed($classname)
            && $this->countAssetsLinkedToPeerItem($classname, Infocom::class) > 0;
    }

    public function getCapacityUsageDescription(string $classname): string
    {
        return sprintf(
            __('Used by %1$s of %2$s assets'),
            $this->countAssetsLinkedToPeerItem($classname, Infocom::class),
            $this->countAssets($classname)
        );
    }

    public function onClassBootstrap(string $classname, CapacityConfig $config): void
    {
        $this->registerToTypeConfig('infocom_types', $classname);

        CommonGLPI::registerStandardTab($classname, Infocom::class, 50);
    }

    public function onCapacityDisabled(string $classname, CapacityConfig $config): void
    {
        // Unregister from infocom types
        $this->unregisterFromTypeConfig('infocom_types', $classname);

        // Delete related infocom data
        $infocom = new Infocom();
        $infocom->deleteByCriteria(['itemtype' => $classname], force: true, history: false);

        $infocom_search_options = Infocom::rawSearchOptionsToAdd($classname);

        // Clean history related to infocoms
        $this->deleteFieldsLogs($classname, $infocom_search_options);

        // Clean display preferences
        $this->deleteDisplayPreferences($classname, $infocom_search_options);
    }
}
