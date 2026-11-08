<?php

namespace Glpi\Inventory\Asset;

use Item_DeviceFirmware;

class Bios extends Device
{
    public function prepare(): array
    {
        $mapping = [
            'bdate'           => 'date',
            'bversion'        => 'version',
            'bmanufacturer'   => 'manufacturers_id',
            'biosserial'      => 'serial',
        ];

        $val = (object) $this->data;
        foreach ($mapping as $origin => $dest) {
            if (property_exists($val, $origin)) {
                $val->$dest = $val->$origin;
            }
        }

        $val->designation = sprintf(
            __('%1$s BIOS'),
            property_exists($val, 'bmanufacturer') ? $val->bmanufacturer : ''
        );
        $val->devicefirmwaretypes_id = 'BIOS';

        $this->data = [$val];
        return $this->data;
    }

    public function handle()
    {
        if (isset($this->main_item) && $this->main_item->isPartial()) {
            return;
        }

        parent::handle();
    }

    public function getItemtype(): string
    {
        return Item_DeviceFirmware::class;
    }
}
