<?php

namespace Glpi\Inventory\Asset;

use Glpi\Inventory\Conf;
use Item_DeviceControl;
use PCIVendor;

class Controller extends Device
{
    protected array $extra_data = ['ignored' => null];

    public function prepare(): array
    {
        $mapping = [
            'name'          => 'designation',
            'manufacturer'  => 'manufacturers_id',
            'type'          => 'interfacetypes_id',
            'model'         => 'devicecontrolmodels_id',
        ];
        $pcivendor = new PCIVendor();

        foreach ($this->data as $k => &$val) {
            if (property_exists($val, 'name')) {
                foreach ($mapping as $origin => $dest) {
                    if (property_exists($val, $origin)) {
                        $val->$dest = $val->$origin;
                    }
                }
                if (property_exists($val, 'vendorid')) {
                    //manufacturer
                    if ($pci_manufacturer = $pcivendor->getManufacturer($val->vendorid)) {
                        $val->manufacturers_id = $pci_manufacturer;
                        if (property_exists($val, 'productid')) {
                            //product name
                            if ($pci_product = $pcivendor->getProductName($val->vendorid, $val->productid)) {
                                $val->designation = $pci_product;
                            }
                        }
                    }
                }
                $val->is_dynamic = 1;
            } else {
                unset($this->data[$k]);
            }
        }
        return $this->data;
    }

    public function handle()
    {
        $data = $this->data;

        foreach ($data as $k => $asset) {
            if (property_exists($asset, 'name') && isset($this->extra_data['ignored'][$asset->name])) {
                unset($data[$k]);
            }
        }

        $this->data = $data;
        parent::handle();
    }

    public function checkConf(Conf $conf): bool
    {
        return $conf->component_control == 1 && parent::checkConf($conf);
    }

    public function getItemtype(): string
    {
        return Item_DeviceControl::class;
    }
}
