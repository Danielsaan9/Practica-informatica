<?php

/// Class MonitorType
class MonitorType extends CommonType
{
    public static function getTypeName($nb = 0)
    {
        return _n('Monitor type', 'Monitor types', $nb);
    }
}
