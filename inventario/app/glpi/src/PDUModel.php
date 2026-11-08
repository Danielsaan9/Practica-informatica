<?php

/// Class PDUModel
class PDUModel extends CommonDCModelDropdown
{
    public static function getTypeName($nb = 0)
    {
        return _n('PDU model', 'PDU models', $nb);
    }
}
