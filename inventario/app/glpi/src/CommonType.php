<?php

use Glpi\Features\Clonable;

abstract class CommonType extends CommonDropdown
{
    /** @use Clonable<static> */
    use Clonable;

    public static function getFieldLabel()
    {
        return _n('Type', 'Types', 1);
    }

    public static function getIcon()
    {
        $type_class  = static::class;
        $device_class = str_replace('Type', '', $type_class);
        return $device_class::getIcon();
    }

    public function getCloneRelations(): array
    {
        return [];
    }
}
