<?php

/// Class EnclosureModel
class EnclosureModel extends CommonDCModelDropdown
{
    public static function getTypeName($nb = 0)
    {
        return _n('Enclosure model', 'Enclosure models', $nb);
    }
}
