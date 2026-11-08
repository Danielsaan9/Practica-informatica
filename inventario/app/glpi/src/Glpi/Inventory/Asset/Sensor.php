<?php

namespace Glpi\Inventory\Asset;

use Item_DeviceSensor;

class Sensor extends Device
{
    public function prepare(): array
    {

        $mapping = [
            'manufacturer' => 'manufacturers_id',
            'type'         => 'devicesensortypes_id',
            'name'         => 'designation',
        ];

        foreach ($this->data as &$val) {
            foreach ($mapping as $origin => $dest) {
                if (property_exists($val, $origin)) {
                    $val->$dest = $val->$origin;
                }
            }

            $val->is_dynamic = 1;
        }

        return $this->data;
    }

    public function getItemtype(): string
    {
        return Item_DeviceSensor::class;
    }
}
