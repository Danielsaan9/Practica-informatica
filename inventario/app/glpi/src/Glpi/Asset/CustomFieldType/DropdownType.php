<?php

namespace Glpi\Asset\CustomFieldType;

use CommonDBTM;
use Glpi\Application\View\TemplateRenderer;
use Glpi\Asset\CustomFieldOption\BooleanOption;
use Glpi\DBAL\QueryExpression;
use Glpi\DBAL\QueryFunction;
use Glpi\DBAL\QueryIdentifier;

class DropdownType extends AbstractType
{
    public static function getName(): string
    {
        return _n('Dropdown', 'Dropdowns', 1);
    }

    public function getOptions(): array
    {
        $options = parent::getOptions();
        $options[] = new BooleanOption($this->custom_field, 'multiple', __('Multiple values'));
        return $options;
    }

    public function normalizeValue(mixed $value): mixed
    {
        if ($value === null) {
            return null;
        }
        if ($this->getOptionValues()['multiple'] ?? false) {
            if (!is_array($value)) {
                $value = [$value];
            }
            $value = array_filter($value, static fn($val) => (int) $val > 0);
            $value = array_map(static fn($val) => (int) $val, $value);
            return $value;
        }

        if (is_array($value)) {
            $value = $value[0] ?? '';
        }
        return $value !== '' ? (int) $value : '';
    }

    public function formatValueForDB(mixed $value): mixed
    {
        return $this->normalizeValue($value);
    }

    public function formatValueFromDB(mixed $value): mixed
    {
        if ($value === null) {
            return null;
        }
        $is_multiple = $this->getOptionValues()['multiple'] ?? false;
        if ($is_multiple && !is_array($value)) {
            $value = [$value];
        } elseif (!$is_multiple && is_array($value)) {
            $value = $value[0] ?? '';
        }
        return $value;
    }

    public function getFormInput(string $name, mixed $value, ?string $label = null, bool $for_default = false): string
    {
        $twig_params = [
            'itemtype' => $this->custom_field->fields['itemtype'],
            'name' => $name,
            'value' => $value ?? $this->custom_field->fields['default_value'],
            'label' => $label ?? $this->custom_field->getFriendlyName(),
            'field_options' => $this->getOptionValues($for_default),
        ];
        if ($for_default) {
            $twig_params['field_options']['full_width'] = false;
            if ($twig_params['value'] === '') {
                $twig_params['value'] = null;
            }
        }
        // language=Twig
        return TemplateRenderer::getInstance()->renderFromStringTemplate(<<<TWIG
            {% import 'components/form/fields_macros.html.twig' as fields %}
            {{ fields.dropdownField(itemtype, name, value, label, field_options|merge({
                values: value|default({}),
                entity: session('glpiactiveentities'),
            })) }}
TWIG, $twig_params);
    }

    public function getSearchOption(): ?array
    {
        global $DB;

        /** @var class-string<CommonDBTM> $itemtype */
        $itemtype = $this->custom_field->fields['itemtype'];
        $multiple = $this->custom_field->fields['field_options']['multiple'] ?? false;

        $opt = [
            'id' => $this->custom_field->getSearchOptionID(),
            'name' => $this->custom_field->fields['label'],
            'itemtype' => $itemtype,
            'table' => getTableForItemType($itemtype),
            'field' => $itemtype::getNameField(),
            'linkfield' => 'custom_fields',
            'datatype' => 'itemlink',
            'itemlink_type' => $itemtype,
            'joinparams' => [
                'jointype' => 'custom_condition_only',
            ],
            'field_definition' => $this->custom_field,
        ];

        if (!$multiple) {
            $opt['joinparams']['condition'] = [
                new QueryExpression(
                    'NEWTABLE.' . $DB::quoteName('id') . ' = ' . QueryFunction::jsonUnquote(
                        expression: QueryFunction::jsonExtract([
                            new QueryIdentifier('REFTABLE.custom_fields'),
                            new QueryExpression($DB::quoteValue('$."' . $this->custom_field->fields['id'] . '"')),
                        ])
                    )
                ),
            ];
        } else {
            $opt['joinparams']['condition'] = [
                QueryFunction::jsonContains(
                    new QueryIdentifier('REFTABLE.custom_fields'),
                    new QueryIdentifier('NEWTABLE.id'),
                    '$."' . $this->custom_field->fields['id'] . '"'
                ),
            ];
            $opt['forcegroupby'] = true;
            $opt['usehaving'] = true;
        }

        return $opt;
    }
}
