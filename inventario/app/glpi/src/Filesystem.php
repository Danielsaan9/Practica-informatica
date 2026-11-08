<?php

/// Class Filesystem
class Filesystem extends CommonDropdown
{
    public bool $can_be_translated = false;


    public static function getTypeName($nb = 0)
    {
        return _n('File system', 'File systems', $nb);
    }

    public static function getIcon()
    {
        return "ti ti-folder";
    }
}
