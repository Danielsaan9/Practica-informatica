<?php

namespace Glpi\Dashboard\Filters;

use Group_Item;

class GroupTechFilter extends AbstractGroupFilter
{
    public static function getName(): string
    {
        return sprintf(
            __('%1$s / %2$s'),
            __('Technician group'),
            _n('Assigned group', 'Assigned groups', 1)
        );
    }

    public static function getId(): string
    {
        return "group_tech";
    }

    protected static function getGroupType(): int
    {
        return Group_Item::GROUP_TYPE_TECH;
    }

    protected static function getGroupFieldName(): string
    {
        return 'groups_id_tech';
    }

    protected static function getITILSearchOptionID(): int
    {
        return 8;
    }
}
