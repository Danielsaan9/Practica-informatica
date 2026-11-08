<?php

namespace Glpi\Features;

use Enclosure;
use Rack;

interface DCBreadcrumbInterface
{
    /**
     * Specific value for "Data center position".
     *
     * @param int $items_id
     *
     * @return string
     */
    public static function renderDcBreadcrumb(int $items_id): string;

    /**
     * Get parent Enclosure.
     *
     * @return Enclosure|null
     */
    public function getParentEnclosure(): ?Enclosure;

    /**
     * Get position in Enclosure.
     *
     * @return int|null
     */
    public function getPositionInEnclosure(): ?int;

    /**
     * Get parent Rack.
     *
     * @return Rack|null
     */
    public function getParentRack(): ?Rack;

    /**
     * Get position in Rack.
     *
     * @return int|null
     */
    public function getPositionInRack(): ?int;
}
