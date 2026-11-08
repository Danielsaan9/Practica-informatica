<?php

namespace Glpi\Inventory\Asset;

use Glpi\Inventory\Conf;
use Item_DeviceMemory;

use function Safe\preg_match;

class Memory extends Device
{
    public function prepare(): array
    {
        $mapping = [
            'capacity'     => 'size',
            'speed'        => 'frequence',
            'type'         => 'devicememorytypes_id',
            'manufacturer' => 'manufacturers_id',
            'serialnumber' => 'serial',
            'numslots'     => 'busID',
            'model'        => 'devicememorymodels_id',
        ];

        foreach ($this->data as $k => &$val) {
            if (property_exists($val, 'capacity') && $val->capacity > 0) {
                foreach ($mapping as $origin => $dest) {
                    if (property_exists($val, $origin)) {
                        $val->$dest = $val->$origin;
                    }
                }
            } else {
                unset($this->data[$k]);
                continue;
            }

            // Hack to remove Memories with Flash types see ticket
            // http://forge.fusioninventory.org/issues/1337
            if (
                property_exists($val, 'type')
                && preg_match('/Flash/', $val->type)
            ) {
                unset($this->data[$k]);
                continue;
            }

            $designation = '';
            if (
                property_exists($val, 'type')
                && $val->type != 'Empty Slot'
                && $val->type != 'Unknown'
            ) {
                $designation = $val->type;
            }

            if (property_exists($val, 'frequence')) {
                $val->frequence = str_replace([' MHz', ' MT/s'], '', $val->frequence);
                if ($designation != '') {
                    $designation .= ' - ' . $val->frequence;
                }
            }

            if (property_exists($val, 'description')) {
                if ($designation != '') {
                    $designation .= ' - ';
                }
                $designation .= $val->description;
            }

            if ($designation != '') {
                $val->designation = $designation;
            }

            $val->is_dynamic = 1;
        }
        return $this->data;
    }

    public function checkConf(Conf $conf): bool
    {
        return $conf->component_memory == 1 && parent::checkConf($conf);
    }

    public function getItemtype(): string
    {
        return Item_DeviceMemory::class;
    }
}
