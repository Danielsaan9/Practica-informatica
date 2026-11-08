<?php

namespace Glpi\Asset\Capacity;

use CommonGLPI;
use Glpi\Asset\Asset;
use Glpi\Asset\CapacityConfig;
use Log;
use Override;

class HasHistoryCapacity extends AbstractCapacity
{
    public function getLabel(): string
    {
        return Log::getTypeName();
    }

    public function getIcon(): string
    {
        return Log::getIcon();
    }

    #[Override]
    public function getDescription(): string
    {
        return __("Records the modifications made to the asset");
    }

    public function isUsed(string $classname): bool
    {
        return parent::isUsed($classname)
            && $this->countAssetsLinkedToPeerItem($classname, Log::class) > 0;
    }

    public function getCapacityUsageDescription(string $classname): string
    {
        return sprintf(
            __('%1$s logs attached to %2$s assets'),
            $this->countPeerItemsUsage($classname, Log::class),
            $this->countAssetsLinkedToPeerItem($classname, Log::class)
        );
    }

    public function onClassBootstrap(string $classname, CapacityConfig $config): void
    {
        CommonGLPI::registerStandardTab(
            $classname,
            Log::class,
            PHP_INT_MAX // PHP_INT_MAX to ensure that tab is always the latest
        );
    }

    public function onObjectInstanciation(Asset $object, CapacityConfig $config): void
    {
        $object->dohistory = true;
    }

    public function onCapacityDisabled(string $classname, CapacityConfig $config): void
    {
        global $DB;

        // Do not use `CommonDBTM::deleteByCriteria()` to prevent performances issues
        $DB->delete(Log::getTable(), ['itemtype' => $classname]);
    }
}
