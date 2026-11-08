<?php

namespace Glpi\Inventory\Asset;

use Glpi\Inventory\Conf;
use Item_DeviceBattery;

class Battery extends Device
{
    public function prepare(): array
    {
        $mapping = [
            'name'         => 'designation',
            'manufacturer' => 'manufacturers_id',
            'serial'       => 'serial',
            'date'         => 'manufacturing_date',
            'capacity'     => 'capacity',
            'chemistry'    => 'devicebatterytypes_id',
            'voltage'      => 'voltage',
        ];

        foreach ($this->data as &$val) {
            foreach ($mapping as $origin => $dest) {
                if (property_exists($val, $origin)) {
                    $val->$dest = $val->$origin;
                }
            }

            if (!isset($val->voltage) || $val->voltage == '') {
                //a numeric value is expected here
                $val->voltage = 0;
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
        return $conf->component_battery == 1 && parent::checkConf($conf);
    }

    public function getItemtype(): string
    {
        return Item_DeviceBattery::class;
    }
}
