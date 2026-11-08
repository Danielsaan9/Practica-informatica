<?php

namespace Glpi\Inventory\Asset;

use Glpi\Inventory\Conf;
use Item_RemoteManagement;
use RuntimeException;

class RemoteManagement extends InventoryAsset
{
    public function prepare(): array
    {
        global $CFG_GLPI;

        if (!in_array($this->item::class, $CFG_GLPI['remote_management_types'])) {
            throw new RuntimeException(
                'Remote Management are handled for following types only: '
                . implode(', ', $CFG_GLPI['remote_management_types'])
            );
        }

        $mapping = [
            'id'      => 'remoteid',
        ];

        foreach ($this->data as &$val) {
            foreach ($mapping as $origin => $dest) {
                if (property_exists($val, $origin)) {
                    $val->$dest = $val->$origin;
                }
            }

            unset($val->id);
            $val->is_dynamic = 1;
        }

        return $this->data;
    }

    /**
     * Get existing entries from database
     *
     * @return array<int, array<string, mixed>>
     */
    protected function getExisting(): array
    {
        global $DB;

        $db_existing = [];

        $iterator = $DB->request([
            'SELECT' => ['id', 'remoteid', 'type', 'is_dynamic'],
            'FROM'   => Item_RemoteManagement::getTable(),
            'WHERE'  => [
                'itemtype' => $this->item::class,
                'items_id' => $this->item->fields['id'],
            ],
        ]);
        foreach ($iterator as $data) {
            $idtmp = $data['id'];
            unset($data['id']);
            $data = array_map('strtolower', $data);
            $db_existing[$idtmp] = $data;
        }

        return $db_existing;
    }

    public function handle()
    {
        $db_mgmt = $this->getExisting();
        $value = $this->data;
        $mgmt = new Item_RemoteManagement();

        foreach ($value as $k => $val) {
            $compare = ['remoteid' => $val->remoteid, 'type' => $val->type];
            $compare = array_map('strtolower', $compare);
            foreach ($db_mgmt as $keydb => $arraydb) {
                unset($arraydb['is_dynamic']);
                if ($compare == $arraydb) {
                    $input = (array) $val + [
                        'id'           => $keydb,
                    ];
                    $mgmt->update($input);
                    unset($value[$k]);
                    unset($db_mgmt[$keydb]);
                    break;
                }
            }
        }

        if (!$this->main_asset || !$this->main_asset->isPartial()) {
            foreach ($db_mgmt as $idtmp => $data) {
                if ($data['is_dynamic']) {
                    $mgmt->delete(['id' => $idtmp], true);
                }
            }
        }

        foreach ($value as $val) {
            $val->itemtype = $this->item::class;
            $val->items_id = $this->item->fields['id'];
            $val->is_dynamic = 1;
            $mgmt->add((array) $val);
        }
    }

    public function checkConf(Conf $conf): bool
    {
        global $CFG_GLPI;
        return in_array($this->item::class, $CFG_GLPI['remote_management_types']);
    }

    public function getItemtype(): string
    {
        return Item_RemoteManagement::class;
    }
}
