<?php

namespace Glpi\ContentTemplates\Parameters;

use Group;

/**
 * Parameters for "Group" items.
 *
 * @since 10.0.0
 */
class GroupParameters extends TreeDropdownParameters
{
    public static function getDefaultNodeName(): string
    {
        return 'group';
    }

    public static function getObjectLabel(): string
    {
        return Group::getTypeName(1);
    }

    protected function getTargetClasses(): array
    {
        return [Group::class];
    }
}
