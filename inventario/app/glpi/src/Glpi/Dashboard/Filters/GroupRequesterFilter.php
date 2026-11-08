<?php

namespace Glpi\Dashboard\Filters;

use Group_Item;

class GroupRequesterFilter extends AbstractGroupFilter
{
    public static function getName(): string
    {
        return __("Group / Requester group");
    }

    public static function getId(): string
    {
        return "group_requester";
    }

    protected static function getGroupType(): int
    {
        return Group_Item::GROUP_TYPE_NORMAL;
    }

    protected static function getGroupFieldName(): string
    {
        return 'groups_id';
    }

    protected static function getITILSearchOptionID(): int
    {
        return 71;
    }
}
