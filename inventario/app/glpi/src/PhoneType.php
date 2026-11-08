<?php

/// Class PhoneType
class PhoneType extends CommonType
{
    public static function getTypeName($nb = 0)
    {
        return _n('Phone type', 'Phone types', $nb);
    }
}
