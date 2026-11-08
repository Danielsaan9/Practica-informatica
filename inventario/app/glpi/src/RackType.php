<?php

/// Class RackType
class RackType extends CommonType
{
    public static function getTypeName($nb = 0)
    {
        return _n('Rack type', 'Rack types', $nb);
    }
}
