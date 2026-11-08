<?php

namespace Glpi\ContentTemplates\Parameters;

use CommonDBTM;
use Glpi\ContentTemplates\Parameters\ParametersTypes\ObjectParameter;
use Group;
use OLA;

/**
 * Parameters for "OLA" items.
 *
 * @since 10.0.0
 */
class OLAParameters extends LevelAgreementParameters
{
    public function getAvailableParameters(): array
    {
        $parameters = parent::getAvailableParameters();
        $parameters[] = new ObjectParameter(new GroupParameters());
        return $parameters;
    }

    protected function defineValues(CommonDBTM $la): array
    {
        $values = parent::defineValues($la);

        if ($group = Group::getById($la->fields['groups_id'])) {
            $values['group'] = (new GroupParameters())->getValues($group);
        } else {
            $values['group'] = null;
        }

        return $values;
    }

    public static function getDefaultNodeName(): string
    {
        return 'ola';
    }

    public static function getObjectLabel(): string
    {
        return OLA::getTypeName(1);
    }

    protected function getTargetClasses(): array
    {
        return [OLA::class];
    }
}
