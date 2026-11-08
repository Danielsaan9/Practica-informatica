<?php

/// Class PlugType
class PlugType extends CommonType
{
    public static function getTypeName($nb = 0)
    {
        return _n('Plug type', 'Plug types', $nb);
    }
}
