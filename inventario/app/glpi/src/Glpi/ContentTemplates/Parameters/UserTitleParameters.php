<?php

namespace Glpi\ContentTemplates\Parameters;

use UserTitle;

/**
 * Parameters class for "UserTitle" items.
 *
 * @since 10.0.0
 */
class UserTitleParameters extends DropdownParameters
{
    public static function getDefaultNodeName(): string
    {
        return 'usertitle';
    }

    public static function getObjectLabel(): string
    {
        return UserTitle::getTypeName(1);
    }

    protected function getTargetClasses(): array
    {
        return [UserTitle::class];
    }
}
