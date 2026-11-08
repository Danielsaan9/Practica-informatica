<?php

/// Class SoftwareLicenseType
class SoftwareLicenseType extends CommonTreeDropdown
{
    public bool $can_be_translated       = true;

    public static function getTypeName($nb = 0)
    {
        return _n('License type', 'License types', $nb);
    }

    public static function getFieldLabel()
    {
        return _n('Type', 'Types', 1);
    }

    public static function getIcon()
    {
        return SoftwareLicense::getIcon();
    }
}
