<?php

namespace Glpi\ContentTemplates\Parameters;

use Location;

/**
 * Parameters for "Location" items.
 *
 * @since 10.0.0
 */
class LocationParameters extends TreeDropdownParameters
{
    public static function getDefaultNodeName(): string
    {
        return 'location';
    }

    public static function getObjectLabel(): string
    {
        return Location::getTypeName(1);
    }

    protected function getTargetClasses(): array
    {
        return [Location::class];
    }
}
