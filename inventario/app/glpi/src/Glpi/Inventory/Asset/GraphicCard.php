<?php

namespace Glpi\Inventory\Asset;

use Glpi\Inventory\Conf;
use Item_DeviceGraphicCard;

class GraphicCard extends Device
{
    protected array $ignored = ['controllers' => null];

    public function prepare(): array
    {
        $mapping = [
            'name'   => 'designation',
        ];

        foreach ($this->data as $k => &$val) {
            if (property_exists($val, 'name')) {
                foreach ($mapping as $origin => $dest) {
                    if (property_exists($val, $origin)) {
                        $val->$dest = $val->$origin;
                    }
                }

                $this->ignored['controllers'][$val->name] = $val->name;
                if (isset($val->chipset)) {
                    $this->ignored['controllers'][$val->chipset] = $val->chipset;
                }

                $val->is_dynamic = 1;
            } else {
                unset($this->data[$k]);
            }
        }
        return $this->data;
    }

    public function checkConf(Conf $conf): bool
    {
        return $conf->component_graphiccard == 1 && parent::checkConf($conf);
    }

    public function getItemtype(): string
    {
        return Item_DeviceGraphicCard::class;
    }
}
