<?php

namespace Glpi\Inventory\MainAsset;

use CommonDBTM;
use Glpi\Inventory\Asset\Plug as AssetPlug;
use Glpi\Inventory\Conf;
use PDUModel;
use PDUType;
use stdClass;

class PDU extends NetworkEquipment
{
    protected Conf $conf;
    private AssetPlug $plugs;

    public function __construct(CommonDBTM $item, $data)
    {
        parent::__construct($item, $data);
    }

    public function prepare(): array
    {
        parent::prepare();

        $inventory_plugs = [];
        foreach ($this->data as &$val) {
            /** @var stdClass $val */

            // asset type is defined under 'pdu->type'
            if (property_exists($val, 'pdu') && property_exists($val->pdu, 'type')) {
                $val->pdutypes_id = $val->pdu->type;
                unset($val->pdu->type);
            }

            if (property_exists($val, 'pdu') && property_exists($val->pdu, 'plugs')) {
                $inventory_plugs[] = $val->pdu->plugs;
            }
        }


        $this->plugs = new AssetPlug($this->item);
        $this->plugs->setData($inventory_plugs);
        $this->plugs->prepare();

        return $this->data;
    }

    public function handle()
    {
        parent::handle();
        if (isset($this->plugs) && !$this->item->isNewItem()) {
            $this->plugs
                ->setMainAsset($this)
                ->setAgent($this->getAgent())
                ->setEntityID($this->getEntityID())
                ->setEntityRecursive($this->getEntityRecursive());
            $this->plugs->handle();
        }
    }

    public function checkConf(Conf $conf): bool
    {
        global $CFG_GLPI;
        return parent::checkConf($conf)
            && $conf->import_pdu == 1
            && in_array($this->item::class, $CFG_GLPI['plug_types']);
    }

    protected function getModelsFieldName(): string
    {
        return PDUModel::getForeignKeyField();
    }

    protected function getTypesFieldName(): string
    {
        return PDUType::getForeignKeyField();
    }

}
