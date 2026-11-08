<?php

namespace Glpi\Helpdesk\Tile;

use Glpi\Session\SessionInfo;

interface TileInterface
{
    /**
     * Used to sort tiles in dropdowns.
     * Lowest weight will be displayed first.
     */
    public function getWeight(): int;

    public function getLabel(): string;

    public function getTitle(): string;

    public function getDescription(): string;

    public function getIllustration(): string;

    public function getTileUrl(): string;

    public function isAvailable(SessionInfo $session_info): bool;

    public function getDatabaseId(): int;

    public function getConfigFieldsTemplate(): string;
}
