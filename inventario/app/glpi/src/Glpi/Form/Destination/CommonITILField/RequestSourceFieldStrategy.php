<?php

namespace Glpi\Form\Destination\CommonITILField;

use Glpi\Form\AnswersSet;

enum RequestSourceFieldStrategy: string
{
    case FROM_TEMPLATE = 'from_template';
    case SPECIFIC_VALUE = 'specific_value';

    public function getLabel(): string
    {
        return match ($this) {
            self::FROM_TEMPLATE     => __("From template"),
            self::SPECIFIC_VALUE    => __("Specific request source"),
        };
    }

    public function getRequestSource(
        RequestSourceFieldConfig $config,
        AnswersSet $answers_set,
    ): ?int {
        return match ($this) {
            self::FROM_TEMPLATE => null, // Let the template apply its default value by itself.
            self::SPECIFIC_VALUE => $config->getSpecificRequestSource(),
        };
    }
}
