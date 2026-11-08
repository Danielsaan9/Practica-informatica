<?php

namespace Glpi\Form\Destination\CommonITILField;

use Glpi\Form\AnswersSet;

enum TemplateFieldStrategy: string
{
    case DEFAULT_TEMPLATE = 'default_template';
    case SPECIFIC_TEMPLATE = 'specific_template';

    public function getLabel(): string
    {
        return match ($this) {
            self::DEFAULT_TEMPLATE     => __("Default template"),
            self::SPECIFIC_TEMPLATE    => __("Specific template"),
        };
    }

    public function getTemplateID(
        TemplateFieldConfig $config,
        AnswersSet $answers_set,
    ): ?int {
        return match ($this) {
            self::DEFAULT_TEMPLATE => null, // Use default template
            self::SPECIFIC_TEMPLATE => $config->getSpecificTemplateID()
        };
    }
}
