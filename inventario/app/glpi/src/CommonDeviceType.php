<?php

abstract class CommonDeviceType extends CommonType
{
    public static function getTypeName($nb = 0)
    {
        return _n('Device type', 'Device types', $nb);
    }

    public static function getFormURL($full = true)
    {
        global $CFG_GLPI;

        $dir = ($full ? $CFG_GLPI['root_doc'] : '');
        $itemtype = static::class;
        $link = "$dir/front/devicetype.form.php?itemtype=$itemtype";

        return $link;
    }

    public static function getSearchURL($full = true)
    {
        global $CFG_GLPI;

        $dir = ($full ? $CFG_GLPI['root_doc'] : '');
        $itemtype = static::class;
        $link = "$dir/front/devicetype.php?itemtype=$itemtype";

        return $link;
    }
}
