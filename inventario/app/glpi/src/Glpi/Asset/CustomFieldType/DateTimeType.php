<?php

namespace Glpi\Asset\CustomFieldType;

use Glpi\Application\View\TemplateRenderer;
use InvalidArgumentException;

use function Safe\preg_match;
use function Safe\strtotime;

class DateTimeType extends AbstractType
{
    public static function getName(): string
    {
        return __('Date and time');
    }

    public function getFormInput(string $name, mixed $value, ?string $label = null, bool $for_default = false): string
    {
        $twig_params = [
            'name' => $name,
            'value' => $value ?? $this->custom_field->fields['default_value'],
            'label' => $label ?? $this->custom_field->getFriendlyName(),
            'field_options' => $this->getOptionValues($for_default) + ['clearable' => true],
        ];
        // language=Twig
        return TemplateRenderer::getInstance()->renderFromStringTemplate(<<<TWIG
            {% import 'components/form/fields_macros.html.twig' as fields %}
            {{ fields.datetimeField(name, value, label, field_options) }}
TWIG, $twig_params);
    }

    public function normalizeValue(mixed $value): ?string
    {
        if (empty($value)) {
            return null;
        }
        if (!preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $value)) {
            throw new InvalidArgumentException('The value must be in the format YYYY-MM-DD HH:MM:SS');
        }
        return gmdate('Y-m-d H:i:s', strtotime($value));
    }

    public function formatValueFromDB(mixed $value): ?string
    {
        if (empty($value)) {
            return null;
        }
        return date('Y-m-d H:i:s', strtotime($value . ' UTC'));
    }

    public function getSearchOption(): ?array
    {
        $opt = $this->getCommonSearchOptionData();
        $opt['datatype'] = 'datetime';
        return $opt;
    }
}
