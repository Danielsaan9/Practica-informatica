<?php

/**
 * Local instantiation of NetworkPort. Among others, loopback (ie.: 127.0.0.1).
 * @since 0.84
 */
class NetworkPortLocal extends NetworkPortInstantiation
{
    public bool $canHaveVLAN = false;
    public bool $haveMAC     = false;

    public static function getTypeName($nb = 0)
    {
        return __('Local loop port');
    }
}
