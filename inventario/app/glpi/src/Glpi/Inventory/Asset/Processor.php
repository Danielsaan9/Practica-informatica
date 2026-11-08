<?php

namespace Glpi\Inventory\Asset;

use Glpi\Inventory\Conf;
use Item_DeviceProcessor;

class Processor extends Device
{
    public function prepare(): array
    {
        $mapping = [
            'speed'        => 'frequency',
            'manufacturer' => 'manufacturers_id',
            'serial'       => 'serial',
            'name'         => 'designation',
            'core'         => 'nbcores',
            'thread'       => 'nbthreads',
            'id'           => 'internalid',
        ];
        foreach ($this->data as &$val) {
            foreach ($mapping as $origin => $dest) {
                if (property_exists($val, $origin)) {
                    $val->$dest = $val->$origin;
                }
            }
            if (property_exists($val, 'frequency')) {
                $val->frequency_default = $val->frequency;
                $val->frequence = $val->frequency;
            } else {
                $val->frequency_default = 0;
                $val->frequency = 0;
                $val->frequence = 0;
            }
            if (property_exists($val, 'type')) {
                $val->designation = $val->type;
            }
            unset($val->id);
            $val->is_dynamic = 1;
        }
        return $this->data;
    }

    public function checkConf(Conf $conf): bool
    {
        return $conf->component_processor == 1 && parent::checkConf($conf);
    }

    public function getItemtype(): string
    {
        return Item_DeviceProcessor::class;
    }
}
