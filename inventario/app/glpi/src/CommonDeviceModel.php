<?php

use Glpi\Features\Clonable;

/// Class DeviceBatteryModel
abstract class CommonDeviceModel extends CommonDropdown
{
    /** @use Clonable<static> */
    use Clonable;

    public static function getTypeName($nb = 0)
    {
        return _n('Device model', 'Device models', $nb);
    }

    public static function getFormURL($full = true)
    {
        global $CFG_GLPI;

        $dir = ($full ? $CFG_GLPI['root_doc'] : '');
        $itemtype = static::class;
        $link = "$dir/front/devicemodel.form.php?itemtype=$itemtype";

        return $link;
    }

    public static function getSearchURL($full = true)
    {
        global $CFG_GLPI;

        $dir = ($full ? $CFG_GLPI['root_doc'] : '');
        $itemtype = static::class;
        $link = "$dir/front/devicemodel.php?itemtype=$itemtype";

        return $link;
    }

    public static function getIcon()
    {
        $model_class  = static::class;
        $device_class = str_replace('Model', '', $model_class);
        return $device_class::getIcon();
    }

    public function getCloneRelations(): array
    {
        return [];
    }
}
