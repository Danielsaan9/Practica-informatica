<?php

/// Class ComputerModel
class ComputerModel extends CommonDCModelDropdown
{
    public static function getTypeName($nb = 0)
    {
        return _n('Computer model', 'Computer models', $nb);
    }
}
