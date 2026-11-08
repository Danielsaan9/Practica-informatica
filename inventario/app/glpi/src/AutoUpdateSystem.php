<?php

class AutoUpdateSystem extends CommonDropdown
{
    public const NATIVE_INVENTORY = "GLPI Native Inventory";

    public static function getTypeName($nb = 0)
    {
        return _n('Update Source', 'Update Sources', $nb);
    }

    public static function getLabelFor(string $key): string
    {
        switch ($key) {
            case self::NATIVE_INVENTORY:
                return __('GLPI Native Inventory');
        }

        return '';
    }
}
