<?php

class DomainType extends CommonDropdown
{
    public static string $rightname = 'dropdown';

    public static function getTypeName($nb = 0)
    {
        return _n('Domain type', 'Domain types', $nb);
    }
}
