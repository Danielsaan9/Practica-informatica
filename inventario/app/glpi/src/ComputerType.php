<?php

/// Class ComputerType
class ComputerType extends CommonType
{
    public static function getTypeName($nb = 0)
    {
        return _n('Computer type', 'Computer types', $nb);
    }
}
