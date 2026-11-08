<?php

namespace Glpi\ContentTemplates\Parameters;

use CommonDBTM;
use Glpi\ContentTemplates\Parameters\ParametersTypes\AttributeParameter;
use LevelAgreement;

/**
 * Parameters for "LevelAgreement" items.
 *
 * @since 10.0.0
 */
abstract class LevelAgreementParameters extends AbstractParameters
{
    public function getAvailableParameters(): array
    {
        return [
            new AttributeParameter("id", __('ID')),
            new AttributeParameter("name", __('Name')),
            new AttributeParameter("type", _n('Type', 'Types', 1)),
            new AttributeParameter("duration", __('Duration')),
            new AttributeParameter("unit", __('Duration unit')),
        ];
    }

    protected function defineValues(CommonDBTM $la): array
    {
        $fields = $la->fields;

        return [
            'id'       => $fields['id'],
            'name'     => $fields['name'],
            'type'     => LevelAgreement::getOneTypeName($fields['type']),
            'duration' => $fields['number_time'],
            'unit'     => strtolower(LevelAgreement::getDefinitionTimeLabel($fields['definition_time'])),
        ];
    }
}
