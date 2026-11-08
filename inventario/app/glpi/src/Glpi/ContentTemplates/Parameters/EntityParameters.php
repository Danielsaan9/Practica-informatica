<?php

namespace Glpi\ContentTemplates\Parameters;

use Entity;

/**
 * Parameters for "Entity" items.
 *
 * @since 10.0.0
 */
class EntityParameters extends TreeDropdownParameters
{
    public static function getDefaultNodeName(): string
    {
        return 'entity';
    }

    public static function getObjectLabel(): string
    {
        return Entity::getTypeName(1);
    }

    protected function getTargetClasses(): array
    {
        return [Entity::class];
    }
}
