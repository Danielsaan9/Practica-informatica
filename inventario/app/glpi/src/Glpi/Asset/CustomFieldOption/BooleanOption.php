<?php

namespace Glpi\Asset\CustomFieldOption;

use Glpi\Application\View\TemplateRenderer;

class BooleanOption extends AbstractOption
{
    public function getFormInput(): string
    {
        $twig_params = [
            'item' => $this->custom_field,
            'key' => $this->getKey(),
            'label' => $this->getName(),
            'value' => $this->getValue(),
        ];
        // language=Twig
        return TemplateRenderer::getInstance()->renderFromStringTemplate(<<<TWIG
            {% import 'components/form/fields_macros.html.twig' as fields %}
            {{ fields.sliderField('field_options[' ~ key ~ ']', value, label) }}
        TWIG, $twig_params);
    }

    public function getValue(): bool
    {
        return (bool) parent::getValue();
    }
}
