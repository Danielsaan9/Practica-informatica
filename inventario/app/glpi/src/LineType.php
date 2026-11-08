<?php

class LineType extends CommonType
{
    public static function getTypeName($nb = 0)
    {
        return _n('Line type', 'Line types', $nb);
    }
}
