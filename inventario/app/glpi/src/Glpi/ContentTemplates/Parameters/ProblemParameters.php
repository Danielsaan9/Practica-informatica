<?php

namespace Glpi\ContentTemplates\Parameters;

use Problem;

/**
 * Parameters for "Problem" items.
 *
 * @since 10.0.0
 */
class ProblemParameters extends CommonITILObjectParameters
{
    public static function getDefaultNodeName(): string
    {
        return 'problem';
    }

    public static function getObjectLabel(): string
    {
        return Problem::getTypeName(1);
    }

    protected function getTargetClasses(): array
    {
        return [Problem::class];
    }
}
