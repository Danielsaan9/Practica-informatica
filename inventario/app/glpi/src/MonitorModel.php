<?php

/// Class MonitorModel
class MonitorModel extends CommonDCModelDropdown
{
    public static function getTypeName($nb = 0)
    {
        return _n('Monitor model', 'Monitor models', $nb);
    }
}
