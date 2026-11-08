<?php

/**
 * Class CartridgeItemType
 **/
class CartridgeItemType extends CommonType
{
    public static function getTypeName($nb = 0)
    {
        return _n('Cartridge type', 'Cartridge types', $nb);
    }
}
