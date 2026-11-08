<?php

namespace Glpi\ContentTemplates\Parameters;

use SLA;

/**
 * Parameters for "SLA" items.
 *
 * @since 10.0.0
 */
class SLAParameters extends LevelAgreementParameters
{
    public static function getDefaultNodeName(): string
    {
        return 'sla';
    }

    public static function getObjectLabel(): string
    {
        return SLA::getTypeName(1);
    }

    protected function getTargetClasses(): array
    {
        return [SLA::class];
    }
}
