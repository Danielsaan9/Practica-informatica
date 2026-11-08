<?php

namespace Glpi\Asset\CustomFieldOption;

use Glpi\Application\View\TemplateRenderer;
use Glpi\Asset\CustomFieldDefinition;

class NumberOption extends AbstractOption
{
    protected float $step;

    public function __construct(CustomFieldDefinition $custom_field, string $key, string $name, float $step = 1, bool $apply_to_default = true, mixed $default_value = null)
    {
        parent::__construct($custom_field, $key, $name, $apply_to_default, $default_value);
        $this->step = $step;
    }

    public function getFormInput(): string
    {
        $twig_params = [
            'item' => $this->custom_field,
            'key' => $this->getKey(),
            'label' => $this->getName(),
            'step' => $this->step,
            'value' => $this->getValue(),
        ];
        // language=Twig
        return TemplateRenderer::getInstance()->renderFromStringTemplate(<<<TWIG
            {% import 'components/form/fields_macros.html.twig' as fields %}
            {{ fields.numberField('field_options[' ~ key ~ ']', value, label, {
                step: step
            }) }}
        TWIG, $twig_params);
    }
}
