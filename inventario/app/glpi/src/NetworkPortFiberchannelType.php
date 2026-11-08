<?php

/// Class ComputerType
class NetworkPortFiberchannelType extends CommonDropdown
{
    public static function getTypeName($nb = 0)
    {
        return _n('Fiber type', 'Fiber types', $nb);
    }

    public static function getFieldLabel()
    {
        return _n('Type', 'Types', 1);
    }
}
