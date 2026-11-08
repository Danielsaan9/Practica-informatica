<?php

namespace Glpi\ContentTemplates\Parameters;

use CommonDBTM;
use Glpi\ContentTemplates\Parameters\ParametersTypes\AttributeParameter;
use KnowbaseItem;

/**
 * Parameters for "KnowbaseItem" items.
 *
 * @since 10.0.0
 */
class KnowbaseItemParameters extends AbstractParameters
{
    public static function getDefaultNodeName(): string
    {
        return 'knowbaseitem';
    }

    public static function getObjectLabel(): string
    {
        return KnowbaseItem::getTypeName(1);
    }

    protected function getTargetClasses(): array
    {
        return [KnowbaseItem::class];
    }

    public function getAvailableParameters(): array
    {
        return [
            new AttributeParameter("id", __('ID')),
            new AttributeParameter("name", __('Subject')),
            new AttributeParameter("answer", __('Content'), "raw"),
            new AttributeParameter("link", _n('Link', 'Links', 1), "raw"),
        ];
    }

    protected function defineValues(CommonDBTM $kbi): array
    {
        $fields = $kbi->fields;

        return [
            'id'     => $fields['id'],
            'name'   => $fields['name'],
            'answer' => $fields['answer'],
            'link'   => $kbi->getLink(),
        ];
    }
}
