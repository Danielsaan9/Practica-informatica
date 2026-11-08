<?php

namespace Glpi\Asset\Capacity;

use CommonDBTM;
use CommonGLPI;
use Glpi\Asset\CapacityConfig;
use Link;
use Link_Itemtype;
use ManualLink;
use Override;
use Session;

class HasLinksCapacity extends AbstractCapacity
{
    public function getLabel(): string
    {
        return sprintf('%s / %s', ManualLink::getTypeName(Session::getPluralNumber()), Link::getTypeName(Session::getPluralNumber()));
    }

    public function getIcon(): string
    {
        return ManualLink::getIcon();
    }

    #[Override]
    public function getDescription(): string
    {
        return __("Define associated external links for the assets");
    }

    public function getCloneRelations(): array
    {
        return [
            ManualLink::class,
        ];
    }

    public function isUsed(string $classname): bool
    {
        return parent::isUsed($classname)
            && (
                $this->countAssetsLinkedToPeerItem($classname, ManualLink::class) > 0
                || $this->countExternalLinks($classname) > 0
            );
    }

    public function getCapacityUsageDescription(string $classname): string
    {
        $manualLinkCount = $this->countPeerItemsUsage($classname, ManualLink::class);
        $externalLinkCount = $this->countExternalLinks($classname);

        // External links are linked to the asset, but not to the item
        // So, if we have external links, all assets of this type are linked
        if ($externalLinkCount > 0) {
            $max = $this->countAssets($classname);
        } else {
            $max = $this->countAssetsLinkedToPeerItem($classname, ManualLink::class);
        }

        return sprintf(__('%1$s links attached to %2$s assets'), $manualLinkCount + $externalLinkCount, $max);
    }

    public function onClassBootstrap(string $classname, CapacityConfig $config): void
    {
        $this->registerToTypeConfig('link_types', $classname);

        CommonGLPI::registerStandardTab($classname, ManualLink::class, 100);
    }

    public function onCapacityDisabled(string $classname, CapacityConfig $config): void
    {
        // Unregister from link types
        $this->unregisterFromTypeConfig('link_types', $classname);

        // Delete related links
        $manual_link = new ManualLink();
        $manual_link->deleteByCriteria([
            'itemtype' => $classname,
        ], true, false);

        $link_itemtype = new Link_Itemtype();
        $link_itemtype->deleteByCriteria([
            'itemtype' => $classname,
        ], true, false);

        // Clean history related to links
        $this->deleteRelationLogs($classname, ManualLink::class);

        // Clean display preferences
        $this->deleteDisplayPreferences($classname, ManualLink::rawSearchOptionsToAdd($classname));
        $this->deleteDisplayPreferences($classname, Link::rawSearchOptionsToAdd($classname));
    }

    /**
     * Count external links defined for given asset class.
     *
     * @param class-string<CommonDBTM> $asset_classname
     * @return int
     */
    private function countExternalLinks(string $asset_classname): int
    {
        return countElementsInTable(
            Link_Itemtype::getTable(),
            [
                'itemtype' => $asset_classname,
            ]
        );
    }
}
