<?php

namespace Glpi\Inventory\Asset;

use Glpi\Inventory\Conf;
use Item_DeviceSoundCard;

class SoundCard extends Device
{
    protected array $ignored = ['controllers' => null];

    public function prepare(): array
    {
        $mapping = [
            'name'          => 'designation',
            'manufacturer'  => 'manufacturers_id',
            'description'   => 'comment',
        ];
        foreach ($this->data as &$val) {
            foreach ($mapping as $origin => $dest) {
                if (property_exists($val, $origin)) {
                    $val->$dest = $val->$origin;
                }
            }
            $val->is_dynamic = 1;
            $this->ignored['controllers'][$val->name] = $val->name;
        }
        return $this->data;
    }

    public function checkConf(Conf $conf): bool
    {
        return $conf->component_soundcard == 1 && parent::checkConf($conf);
    }

    public function getItemtype(): string
    {
        return Item_DeviceSoundCard::class;
    }
}
