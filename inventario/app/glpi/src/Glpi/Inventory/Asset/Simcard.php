<?php

namespace Glpi\Inventory\Asset;

use Glpi\Inventory\Conf;
use Item_DeviceSimcard;

class Simcard extends Device
{
    public function prepare(): array
    {
        $mapping = [
            'subscriber_id' => 'msin',
        ];

        foreach ($this->data as $k => &$val) {
            foreach ($mapping as $origin => $dest) {
                if (property_exists($val, $origin)) {
                    $val->$dest = $val->$origin;
                }
            }
        }

        return $this->data;
    }

    public function checkConf(Conf $conf): bool
    {
        return $conf->component_simcard == 1 && parent::checkConf($conf);
    }

    public function getItemtype(): string
    {
        return Item_DeviceSimcard::class;
    }
}
