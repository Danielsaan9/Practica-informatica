<?php

namespace Glpi\Inventory\MainAsset;

use PhoneModel;
use PhoneType;

class Phone extends MainAsset
{
    protected function getModelsFieldName(): string
    {
        return PhoneModel::getForeignKeyField();
    }

    protected function getTypesFieldName(): string
    {
        return PhoneType::getForeignKeyField();
    }
}
