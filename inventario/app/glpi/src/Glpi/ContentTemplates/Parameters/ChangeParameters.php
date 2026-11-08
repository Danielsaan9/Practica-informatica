<?php

namespace Glpi\ContentTemplates\Parameters;

use Change;

/**
 * Parameters for "Change" items.
 *
 * @since 10.0.0
 */
class ChangeParameters extends CommonITILObjectParameters
{
    public static function getDefaultNodeName(): string
    {
        return 'change';
    }

    public static function getObjectLabel(): string
    {
        return Change::getTypeName(1);
    }

    protected function getTargetClasses(): array
    {
        return [Change::class];
    }
}
