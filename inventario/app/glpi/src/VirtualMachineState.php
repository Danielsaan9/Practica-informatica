<?php

/// Class Filesystem
class VirtualMachineState extends CommonDropdown
{
    public static function getTypeName($nb = 0)
    {
        return _n('State of the virtual machine', 'States of the virtual machine', $nb);
    }
}
