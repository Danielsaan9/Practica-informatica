<?php

final class ProjectTaskTeamDropdown extends AbstractRightsDropdown
{
    protected static function getAjaxUrl(): string
    {
        global $CFG_GLPI;

        return $CFG_GLPI['root_doc'] . "/ajax/getProjectTaskTeamDropdownValue.php";
    }

    protected static function getTypes(array $options = []): array
    {
        return ProjectTask::getTeamMembersItemtypes();
    }
}
