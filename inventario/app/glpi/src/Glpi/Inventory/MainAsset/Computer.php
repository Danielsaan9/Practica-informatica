<?php

namespace Glpi\Inventory\MainAsset;

use ComputerModel;
use ComputerType;
use NetworkPort as GlobalNetworkPort;
use stdClass;

class Computer extends MainAsset
{
    protected function getModelsFieldName(): string
    {
        return ComputerModel::getForeignKeyField();
    }

    protected function getTypesFieldName(): string
    {
        return ComputerType::getForeignKeyField();
    }


    protected function portUpdated(stdClass $port, int $netports_id): void
    {
        $this->handlePortMetrics($port, $netports_id);
    }

    private function handlePortMetrics(stdClass $port, int $netports_id): void
    {
        $input = (array) $port;
        if (isset($input['ifinbytes'], $input['ifoutbytes'], $input['ifinerrors'], $input['ifouterrors'])) {
            $netport = new GlobalNetworkPort();
            $netport->update([
                'id'          => $netports_id,
                'ifinbytes'   => $input['ifinbytes'],
                'ifoutbytes'  => $input['ifoutbytes'],
                'ifinerrors'  => $input['ifinerrors'],
                'ifouterrors' => $input['ifouterrors'],
                'is_dynamic'  => true,
            ]);
        }
    }
}
