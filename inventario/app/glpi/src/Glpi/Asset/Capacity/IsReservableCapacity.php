<?php

namespace Glpi\Asset\Capacity;

use CommonGLPI;
use Glpi\Asset\CapacityConfig;
use Override;
use Reservation;
use ReservationItem;
use Session;

class IsReservableCapacity extends AbstractCapacity
{
    public function getLabel(): string
    {
        return Reservation::getTypeName(Session::getPluralNumber());
    }

    public function getIcon(): string
    {
        return Reservation::getIcon();
    }

    #[Override]
    public function getDescription(): string
    {
        return __("These assets can be made reservable");
    }

    public function isUsed(string $classname): bool
    {
        return parent::isUsed($classname)
            && $this->countAssetsLinkedToPeerItem($classname, ReservationItem::class) > 0;
    }

    public function getCapacityUsageDescription(string $classname): string
    {
        return sprintf(
            __('Used by %1$s of %2$s assets'),
            $this->countPeerItemsUsage($classname, ReservationItem::class),
            $this->countAssets($classname)
        );
    }

    public function onClassBootstrap(string $classname, CapacityConfig $config): void
    {
        global $CFG_GLPI;
        $this->registerToTypeConfig('reservation_types', $classname);
        // Manually set sector-based JS registration
        $CFG_GLPI['javascript']['assets'][strtolower($classname)] = array_merge(
            $CFG_GLPI['javascript']['assets'][strtolower($classname)] ?? [],
            ['fullcalendar', 'reservations']
        );

        CommonGLPI::registerStandardTab($classname, Reservation::class, 85);
    }

    public function onCapacityDisabled(string $classname, CapacityConfig $config): void
    {
        // Unregister from reservable types
        $this->unregisterFromTypeConfig('reservation_types', $classname);

        // Delete related reservations
        $reservation_item = new ReservationItem();
        $reservation_item->deleteByCriteria([
            'itemtype' => $classname,
        ], true, false);

        // Clean history related to links
        $this->deleteRelationLogs($classname, ReservationItem::class);

        // Clean display preferences
        $this->deleteDisplayPreferences($classname, ReservationItem::rawSearchOptionsToAdd($classname));
    }
}
