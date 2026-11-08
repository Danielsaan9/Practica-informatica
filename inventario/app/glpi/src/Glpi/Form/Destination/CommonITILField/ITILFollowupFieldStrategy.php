<?php

namespace Glpi\Form\Destination\CommonITILField;

enum ITILFollowupFieldStrategy: string
{
    case NO_FOLLOWUP       = 'no_followup';
    case SPECIFIC_VALUES   = 'specific_values';

    public function getLabel(): string
    {
        return match ($this) {
            self::NO_FOLLOWUP       => __("No Followup"),
            self::SPECIFIC_VALUES   => __("Specific Followup templates"),
        };
    }

    public function getITILFollowupTemplatesIDs(
        ITILFollowupFieldConfig $config
    ): ?array {
        return match ($this) {
            self::NO_FOLLOWUP      => null,
            self::SPECIFIC_VALUES  => $config->getSpecificITILFollowupTemplatesIds(),
        };
    }
}
