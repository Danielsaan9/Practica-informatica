<?php

namespace Glpi\Inventory\Asset;

use Glpi\Inventory\Conf;
use Item_Process;

class Process extends InventoryAsset
{
    private Conf $conf;

    public function prepare(): array
    {
        $mapping = [
            'cmd'           => 'cmd',
            'cpuusage'      => 'cpuusage',
            'mem'           => 'memusage',
            'pid'           => 'pid',
            'started'       => 'started',
            'tty'           => 'tty',
            'user'          => 'user',
            'virtualmemory' => 'virtualmemory',
        ];

        foreach ($this->data as $key => &$val) {
            foreach ($mapping as $origin => $dest) {
                if (property_exists($val, $origin)) {
                    $val->$dest = $val->$origin;
                }
            }

            if (!$this->checkConf($this->conf)) {
                unset($this->data[$key]);
                continue;
            }

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
            'SELECT' => ['id', 'cmd', 'pid', 'is_dynamic'],
            'FROM'   => Item_Process::getTable(),
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
        $itemProcess = new Item_Process();
        $db_itemProcess = $this->getExisting();

        $value = $this->data;
        foreach ($value as $key => $val) {
            $db_elt = [];
            foreach (['cmd', 'pid'] as $field) {
                $db_elt[$field] = (property_exists($val, $field) ? strtolower($val->$field) : null);
            }

            foreach ($db_itemProcess as $keydb => $arraydb) {
                unset($arraydb['is_dynamic']);
                if ($db_elt == $arraydb) {
                    $input = (array) $val + [
                        'id'           => $keydb,
                    ];
                    $itemProcess->update($input);
                    unset($value[$key]);
                    unset($db_itemProcess[$keydb]);
                    break;
                }
            }
        }

        if ((!$this->main_asset || !$this->main_asset->isPartial()) && count($db_itemProcess) != 0) {
            // Delete Item_Process in DB
            foreach ($db_itemProcess as $dbid => $data) {
                if ($data['is_dynamic'] == 1) {
                    //Delete only dynamics
                    $itemProcess->delete(['id' => $dbid], true);
                }
            }
        }
        if (count($value)) {
            foreach ($value as $val) {
                $input = (array) $val + [
                    'items_id'     => $this->item->fields['id'],
                    'itemtype'     => $this->item::class,
                ];

                $itemProcess->add($input);
            }
        }
    }

    public function checkConf(Conf $conf): bool
    {
        global $CFG_GLPI;
        $this->conf = $conf;
        return $conf->import_process == 1 && in_array($this->item::class, $CFG_GLPI['process_types']);
    }

    public function getItemtype(): string
    {
        return Item_Process::class;
    }
}
