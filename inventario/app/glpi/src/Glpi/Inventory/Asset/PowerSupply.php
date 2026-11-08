<?php

namespace Glpi\Inventory\Asset;

use Glpi\Inventory\Conf;
use Item_DevicePowerSupply;

class PowerSupply extends Device
{
    public function prepare(): array
    {
        $mapping = [
            'serialnumber'  => 'serial',
            'partnum'       => 'designation',
            'manufacturer'  => 'manufacturers_id',
            'power_max'     => 'power',
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

    public function checkConf(Conf $conf): bool
    {
        return $conf->component_powersupply == 1 && parent::checkConf($conf);
    }

    public function getItemtype(): string
    {
        return Item_DevicePowerSupply::class;
    }
}
