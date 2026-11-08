<?php

/// Class VirtualMachineType
class VirtualMachineType extends CommonDropdown
{
    public bool $can_be_translated = false;


    public static function getTypeName($nb = 0)
    {
        return _n('Virtualization system', 'Virtualization systems', $nb);
    }
}
