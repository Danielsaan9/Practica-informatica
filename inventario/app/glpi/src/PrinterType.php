<?php

/// Class PrinterType
class PrinterType extends CommonType
{
    public static function getTypeName($nb = 0)
    {
        return _n('Printer type', 'Printer types', $nb);
    }
}
