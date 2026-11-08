<?php

/// Class SupplierType
class SupplierType extends CommonType
{
    public static function getTypeName($nb = 0)
    {
        return _n('Third party type', 'Third party types', $nb);
    }
}
