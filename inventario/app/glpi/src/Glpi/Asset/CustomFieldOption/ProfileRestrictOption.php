<?php

namespace Glpi\Asset\CustomFieldOption;

use Glpi\Application\View\TemplateRenderer;

class ProfileRestrictOption extends AbstractOption
{
    public function getFormInput(): string
    {
        $value = parent::getValue();
        if (!is_array($value)) {
            $value = [$value];
        }
        $twig_params = [
            'item' => $this->custom_field,
            'key' => $this->getKey(),
            'label' => $this->getName(),
            'value' => array_filter($value),
            'all_label' => __('All'),
        ];
        // language=Twig
        return TemplateRenderer::getInstance()->renderFromStringTemplate(<<<TWIG
            {% import 'components/form/fields_macros.html.twig' as fields %}
            {{ fields.dropdownField('Profile', 'field_options[' ~ key ~ ']', value, label, {
                multiple: true,
                to_add: {
                    '-1': all_label
                },
                condition: {
                    'interface': 'central'
                }
            }) }}
        TWIG, $twig_params);
    }

    public function getValue(): bool
    {
        $value = parent::getValue() ?? [];

        if (!is_array($value)) {
            $value = [$value];
        }

        // Handle special 'All' value
        if (in_array(-1, $value, true)) {
            return true;
        }

        $active_profile = $_SESSION['glpiactiveprofile']['id'] ?? null;
        if ($active_profile === null) {
            return false;
        }
        return in_array($active_profile, $value, false);
    }
}
