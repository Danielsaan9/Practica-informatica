<?php

/// Class ConsumableItemType
class ConsumableItemType extends CommonType
{
    public static function getTypeName($nb = 0)
    {
        return _n('Consumable type', 'Consumable types', $nb);
    }
}
