<?php

namespace Glpi\ContentTemplates\Parameters;

use CommonDBTM;
use Glpi\ContentTemplates\Parameters\ParametersTypes\AttributeParameter;

/**
 * Abstract parameters class for "CommonDropdown" items.
 *
 * @since 10.0.0
 */
abstract class DropdownParameters extends AbstractParameters
{
    public function getAvailableParameters(): array
    {
        return [
            new AttributeParameter("id", __('ID')),
            new AttributeParameter("name", __('Name')),
        ];
    }

    protected function defineValues(CommonDBTM $item): array
    {
        $fields = $item->fields;

        return [
            'id'   => $fields['id'],
            'name' => $fields['name'],
        ];
    }
}
