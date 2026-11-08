<?php

namespace Glpi\Inventory\Asset;

use Glpi\Inventory\Conf;
use Item_DeviceHardDrive;

class HardDrive extends Device
{
    public function prepare(): array
    {
        $mapping = [
            'disksize'      => 'capacity',
            'interface'     => 'interfacetypes_id',
            'type'          => 'deviceharddrivetypes_id',
            'manufacturer'  => 'manufacturers_id',
            'model'         => 'designation',
        ];

        foreach ($this->data as &$val) {
            foreach ($mapping as $origin => $dest) {
                if (property_exists($val, $origin)) {
                    $val->$dest = $val->$origin;
                }
            }

            if ((!property_exists($val, 'model') || $val->model == '') && property_exists($val, 'name')) {
                $val->designation = $val->name;
            }

            if (!isset($val->capacity) || $val->capacity == '') {
                $val->capacity = 0;
            }

            $val->is_dynamic = 1;
        }

        return $this->data;
    }

    public function checkConf(Conf $conf): bool
    {
        return $conf->component_harddrive == 1 && parent::checkConf($conf);
    }

    public function getItemtype(): string
    {
        return Item_DeviceHardDrive::class;
    }
}
