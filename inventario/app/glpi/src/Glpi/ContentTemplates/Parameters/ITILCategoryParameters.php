<?php

namespace Glpi\ContentTemplates\Parameters;

use ITILCategory;

/**
 * Parameters for "ITILCategory" items.
 *
 * @since 10.0.0
 */
class ITILCategoryParameters extends TreeDropdownParameters
{
    public static function getDefaultNodeName(): string
    {
        return 'itilcategory';
    }

    public static function getObjectLabel(): string
    {
        return ITILCategory::getTypeName(1);
    }

    protected function getTargetClasses(): array
    {
        return [ITILCategory::class];
    }
}
