<?php

namespace Glpi\Inventory\MainAsset;

use Glpi\Asset\Asset;

class GenericPrinterAsset extends Printer
{
    protected function getModelsFieldName(): string
    {
        /** @var Asset $item */
        $item = $this->item;
        $model_classname = $item->getDefinition()->getAssetModelClassName();

        return getForeignKeyFieldForItemType($model_classname);
    }

    protected function getTypesFieldName(): string
    {
        /** @var Asset $item */
        $item = $this->item;
        $type_classname = $item->getDefinition()->getAssetTypeClassName();

        return getForeignKeyFieldForItemType($type_classname);
    }
}
