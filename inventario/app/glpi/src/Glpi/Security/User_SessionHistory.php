<?php

namespace Glpi\Security;

use CommonDBTM;

/**
 * Class representing a historical login session entry for a user.
 * Only exists to ease HLAPI support.
 */
class User_SessionHistory extends CommonDBTM
{
    public static function getTable($classname = null)
    {
        return 'glpi_users_sessionhistories';
    }

    public static function canView(): bool
    {
        return true;
    }

    public function canViewItem(): bool
    {
        // Not required by HLAPI. Only here to prevent legacy API leaks.
        return false;
    }
}
