<?php

namespace Glpi\Form\Destination\CommonITILField;

enum ITILTaskFieldStrategy: string
{
    case NO_TASK           = 'no_task';
    case SPECIFIC_VALUES   = 'specific_values';

    public function getLabel(): string
    {
        return match ($this) {
            self::NO_TASK           => __("No Task"),
            self::SPECIFIC_VALUES   => __("Specific Task templates"),
        };
    }

    public function getTaskTemplatesIDs(
        ITILTaskFieldConfig $config
    ): ?array {
        return match ($this) {
            self::NO_TASK          => null,
            self::SPECIFIC_VALUES  => $config->getSpecificTaskTemplatesIds(),
        };
    }
}
