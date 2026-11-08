<?php

/// Class PDUType
class PDUType extends CommonType
{
    public static function getTypeName($nb = 0)
    {
        return _n('PDU type', 'PDU types', $nb);
    }
}
