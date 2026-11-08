<?php

namespace Glpi\Inventory\Asset;

use Glpi\Inventory\Conf;
use Item_Environment;

final class Environment extends InventoryAsset
{
    public function prepare(): array
    {
        foreach ($this->data as $key => &$val) {
            $val->value = $val->val;
            $val->is_dynamic = 1;
        }

        return $this->data;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    protected function getExisting(): array
    {
        global $DB;

        $db_existing = [];

        $iterator = $DB->request([
            'SELECT' => ['id', 'key', 'is_dynamic'],
            'FROM'   => Item_Environment::getTable(),
            'WHERE'  => [
                'items_id' => $this->item->fields['id'],
                'itemtype' => $this->item::class,
            ],
        ]);
        foreach ($iterator as $data) {
            $dbid = $data['id'];
            unset($data['id']);
            $db_existing[$dbid] = [];
            foreach ($data as $key => $value) {
                $db_existing[$dbid][$key] = $value !== null ? strtolower($value) : null;
            }
        }

        return $db_existing;
    }

    public function handle()
    {
        $itemEnv = new Item_Environment();
        $db_itemEnvs = $this->getExisting();

        $value = $this->data;
        foreach ($value as $key => $val) {
            $db_elt = [];
            foreach (['cmd', 'pid'] as $field) {
                $db_elt[$field] = (property_exists($val, $field) ? strtolower($val->$field) : null);
            }

            foreach ($db_itemEnvs as $keydb => $arraydb) {
                unset($arraydb['is_dynamic']);
                if ($db_elt == $arraydb) {
                    $input = (array) $val + [
                        'id'           => $keydb,
                    ];
                    $itemEnv->update($input);
                    unset($value[$key]);
                    unset($db_itemEnvs[$keydb]);
                    break;
                }
            }
        }

        if ((!$this->main_asset || !$this->main_asset->isPartial()) && count($db_itemEnvs) != 0) {
            // Delete Item_Environment in DB
            foreach ($db_itemEnvs as $dbid => $data) {
                if ($data['is_dynamic'] == 1) {
                    //Delete only dynamics
                    $itemEnv->delete(['id' => $dbid], true);
                }
            }
        }
        if (count($value)) {
            foreach ($value as $val) {
                $input = (array) $val + [
                    'items_id'     => $this->item->fields['id'],
                    'itemtype'     => $this->item::class,
                ];

                $itemEnv->add($input);
            }
        }
    }

    public function checkConf(Conf $conf): bool
    {
        global $CFG_GLPI;
        return $conf->import_env == 1 && in_array($this->item::class, $CFG_GLPI['environment_types']);
    }

    public function getItemtype(): string
    {
        return Item_Environment::class;
    }
}
