<?php

class ShareDashboardDropdown extends AbstractRightsDropdown
{
    protected static function getAjaxUrl(): string
    {
        global $CFG_GLPI;

        return $CFG_GLPI['root_doc'] . "/ajax/getShareDashboardDropdownValue.php";
    }

    protected static function getTypes(array $options = []): array
    {
        return [
            User::class,
            Entity::class,
            Profile::class,
            Group::class,
        ];
    }
}
