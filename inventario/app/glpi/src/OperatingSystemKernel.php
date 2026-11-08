<?php

class OperatingSystemKernel extends CommonDropdown
{
    public bool $can_be_translated = false;

    public static function getTypeName($nb = 0)
    {
        return _n('Kernel', 'Kernels', $nb);
    }
}
