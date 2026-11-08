<?php

/**
 * ProjectType Class
 *
 * @since 0.85
 **/
class ProjectType extends CommonDropdown
{
    public static function getTypeName($nb = 0)
    {
        return _n('Project type', 'Project types', $nb);
    }

    public static function getIcon()
    {
        return "ti ti-category";
    }
}
