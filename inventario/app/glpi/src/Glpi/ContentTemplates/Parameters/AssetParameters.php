<?php

namespace Glpi\ContentTemplates\Parameters;

use CommonDBTM;
use Entity;
use Glpi\ContentTemplates\Parameters\ParametersTypes\AttributeParameter;
use Glpi\ContentTemplates\Parameters\ParametersTypes\ObjectParameter;
use State;

/**
 * Parameters for "Assets" items (Computer, Monitor, ...).
 *
 * @since 10.0.0
 */
class AssetParameters extends AbstractParameters
{
    public static function getDefaultNodeName(): string
    {
        return 'asset';
    }

    public static function getObjectLabel(): string
    {
        return _n('Asset', 'Assets', 1);
    }

    protected function getTargetClasses(): array
    {
        global $CFG_GLPI;
        return $CFG_GLPI["asset_types"];
    }

    public function getAvailableParameters(): array
    {
        return [
            new AttributeParameter("id", __('ID')),
            new AttributeParameter("name", __('Name')),
            new AttributeParameter("itemtype", __('Itemtype')),
            new AttributeParameter("serial", __('Serial number')),
            new AttributeParameter("model", _n('Model', 'Models', 1)),
            new AttributeParameter("type", _n('Type', 'Types', 1)),
            new AttributeParameter("state", __('Status')),
            new AttributeParameter("comment", __('Comments')),
            new ObjectParameter(new EntityParameters()),
        ];
    }

    protected function defineValues(CommonDBTM $asset): array
    {
        $fields = $asset->fields;

        $values = [
            'id'       => $fields['id'],
            'name'     => $fields['name'],
            'itemtype' => $asset::class,
            'serial'   => $fields['serial'],
            'model'    => '',
            'type'     => '',
            'state'    => '',
            'comment'  => $fields['comment'],
        ];

        // Add model if asset has a model
        $model_class = $asset->getModelClass();
        if ($model_class !== null) {
            $model_fk = $model_class::getForeignKeyField();
            if (isset($fields[$model_fk]) && $fields[$model_fk] > 0) {
                $model = getItemForItemtype($model_class);
                if ($model && $model->getFromDB($fields[$model_fk])) {
                    $values['model'] = $model->getName();
                }
            }
        }

        // Add type if asset has a type
        $type_class = $asset->getTypeClass();
        if ($type_class !== null) {
            $type_fk = $type_class::getForeignKeyField();
            if (isset($fields[$type_fk]) && $fields[$type_fk] > 0) {
                $type = getItemForItemtype($type_class);
                if ($type && $type->getFromDB($fields[$type_fk])) {
                    $values['type'] = $type->getName();
                }
            }
        }

        // Add state if asset has a state
        if (isset($fields['states_id']) && $fields['states_id'] > 0) {
            $state = new State();
            if ($state->getFromDB($fields['states_id'])) {
                $values['state'] = $state->getName();
            }
        }

        // Add asset's entity
        if ($entity = Entity::getById($fields['entities_id'])) {
            $entity_parameters = new EntityParameters();
            $values['entity'] = $entity_parameters->getValues($entity);
        }

        return $values;
    }
}
