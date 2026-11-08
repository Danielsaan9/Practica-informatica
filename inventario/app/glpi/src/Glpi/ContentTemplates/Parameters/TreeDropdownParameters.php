<?php

namespace Glpi\ContentTemplates\Parameters;

use CommonDBTM;
use Glpi\ContentTemplates\Parameters\ParametersTypes\AttributeParameter;

/**
 * Abstract parameters class for "CommonTreeDropdown" items.
 *
 * @since 10.0.0
 */
abstract class TreeDropdownParameters extends DropdownParameters
{
    public function getAvailableParameters(): array
    {
        $parameter = parent::getAvailableParameters();
        $parameter[] = new AttributeParameter("completename", __('Complete name'));
        return $parameter;
    }

    protected function defineValues(CommonDBTM $item): array
    {
        $fields = $item->fields;

        $values = parent::defineValues($item);
        $values['completename'] = $fields['completename'];
        return $values;
    }
}
