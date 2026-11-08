<?php

namespace Glpi\Helpdesk\Tile;

interface LinkableToTilesInterface
{
    public function acceptTiles(): bool;

    public function getTilesConfigInformationText(): ?string;
}
