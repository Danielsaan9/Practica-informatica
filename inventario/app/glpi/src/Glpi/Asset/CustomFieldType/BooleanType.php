<?php

namespace Glpi\Asset\CustomFieldType;

use Glpi\Application\View\TemplateRenderer;
use Glpi\Asset\CustomFieldOption\BooleanOption;
use Glpi\DBAL\QueryExpression;
use Glpi\DBAL\QueryFunction;
use Glpi\DBAL\QueryIdentifier;
use InvalidArgumentException;

class BooleanType extends AbstractType
{
    public static function getName(): string
    {
        return __('Yes/No');
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
            {% if field_options.display_as_slider %}
                {{ fields.sliderField(name, value, label, field_options) }}
            {% else %}
                {{ fields.dropdownYesNo(name, value, label, field_options) }}
            {% endif %}
TWIG, $twig_params);
    }

    public function normalizeValue(mixed $value): ?bool
    {
        if ($value === null) {
            return null;
        }
        $value = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
        if ($value === null) {
            throw new InvalidArgumentException('The value must be a boolean');
        }
        return $value;
    }

    public function getSearchOption(): ?array
    {
        global $DB;

        $opt = $this->getCommonSearchOptionData();
        $opt['datatype'] = 'bool';
        $opt['computation'] = new QueryExpression(QueryFunction::coalesce([
            QueryFunction::jsonUnquote(
                expression: QueryFunction::jsonExtract([
                    new QueryIdentifier('glpi_assets_assets.custom_fields'),
                    new QueryExpression($DB::quoteValue('$."' . $this->custom_field->fields['id'] . '"')),
                ])
            ),
            new QueryExpression($DB::quoteValue($this->custom_field->fields['default_value'])),
        ]) . ' = ' . $DB::quoteValue('true'));
        return $opt;
    }

    public function getOptions(): array
    {
        $opts = parent::getOptions();
        $opts[] = new BooleanOption($this->custom_field, 'display_as_slider', __('Display as slider'));
        return $opts;
    }
}
