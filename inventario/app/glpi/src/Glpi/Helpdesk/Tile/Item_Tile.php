<?php

namespace Glpi\Helpdesk\Tile;

use CommonDBRelation;

final class Item_Tile extends CommonDBRelation
{
    // Linked CommonDBTM item
    public static ?string $itemtype_1 = 'itemtype_item';
    public static ?string $items_id_1 = 'items_id_item';

    // Linked CommonDBTM&TileInterface item
    public static ?string $itemtype_2 = 'itemtype_tile';
    public static ?string $items_id_2 = 'items_id_tile';
}
