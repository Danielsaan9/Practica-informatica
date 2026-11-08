<?php

/// Class ContactType
class ContactType extends CommonType
{
    public static function getTypeName($nb = 0)
    {
        return _n('Contact type', 'Contact types', $nb);
    }
}
