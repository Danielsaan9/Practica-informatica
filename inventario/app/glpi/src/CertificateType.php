<?php

/**
 * Class to manage certificate types
 */
class CertificateType extends CommonType
{
    public bool $can_be_translated = true;

    public static function getTypeName($nb = 0)
    {
        return _n('Certificate type', 'Certificate types', $nb);
    }
}
