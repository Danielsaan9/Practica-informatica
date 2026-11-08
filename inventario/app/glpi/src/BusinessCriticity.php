<?php

/**
 * DocumentType Class
 **/
class BusinessCriticity extends CommonTreeDropdown
{
    public bool $can_be_translated = true;

    public static function getTypeName($nb = 0)
    {
        return _n('Business criticity', 'Business criticities', $nb);
    }

    public static function getIcon()
    {
        return "ti ti-briefcase";
    }
}
