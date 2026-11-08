<?php

namespace Glpi\Asset\CustomFieldType;

use Glpi\Application\View\TemplateRenderer;

class URLType extends StringType
{
    public static function getName(): string
    {
        return __('URL');
    }

    public function getFormInput(string $name, mixed $value, ?string $label = null, bool $for_default = false): string
    {
        $twig_params = [
            'name' => $name,
            'value' => $value ?? $this->custom_field->fields['default_value'],
            'label' => $label ?? $this->custom_field->getFriendlyName(),
            'field_options' => $this->getOptionValues($for_default),
        ];
        // language=Twig
        return TemplateRenderer::getInstance()->renderFromStringTemplate(<<<TWIG
            {% import 'components/form/fields_macros.html.twig' as fields %}
            {{ fields.urlField(name, value, label, field_options) }}
TWIG, $twig_params);
    }
}
