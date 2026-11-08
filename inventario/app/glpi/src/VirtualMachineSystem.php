<?php

/// Class VirtualMachineSystem
class VirtualMachineSystem extends CommonDropdown
{
    public bool $can_be_translated = false;


    public static function getTypeName($nb = 0)
    {
        return _n('Virtualization model', 'Virtualization models', $nb);
    }
}
