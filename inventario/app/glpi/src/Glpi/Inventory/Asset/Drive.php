<?php

namespace Glpi\Inventory\Asset;

use Glpi\Inventory\Conf;
use Item_DeviceDrive;
use stdClass;

use function Safe\preg_match;

class Drive extends Device
{
    private Conf $conf;

    private HardDrive $harddrives;
    /** @var stdClass[] */
    private array $prepared_harddrives = [];

    public function prepare(): array
    {
        $mapping = [
            'name'         => 'designation',
            'type'         => 'interfacetypes_id',
            'manufacturer' => 'manufacturers_id',
        ];

        $hdd = [];
        foreach ($this->data as $k => &$val) {
            if ($this->isDrive($val)) { // it's cd-rom / dvd
                foreach ($mapping as $origin => $dest) {
                    if (property_exists($val, $origin)) {
                        $val->$dest = $val->$origin;
                    }
                }

                if (property_exists($val, 'description')) {
                    $val->designation = $val->description;
                }

                $val->is_dynamic = 1;
            } else { // it's harddisk
                $hdd[] = $val;
                unset($this->data[$k]);
            }
        }
        if (count($hdd)) {
            $this->harddrives = new HardDrive($this->item);
            if ($this->harddrives->checkConf($this->conf)) {
                $this->harddrives->setData($hdd);
                $prep_hdds = $this->harddrives->prepare();
                if (defined('TU_USER')) {
                    $this->prepared_harddrives = $prep_hdds;
                }
            }
        }

        return $this->data;
    }

    /**
     * Is current data a drive
     *
     * @param stdClass $data
     *
     * @return bool
     */
    public function isDrive($data)
    {
        $drives_regex = [
            'rom',
            'dvd',
            'blu[\s-]*ray',
            'reader',
            'sd[\s-]*card',
            'micro[\s-]*sd',
            'mmc',
        ];

        foreach ($drives_regex as $regex) {
            foreach (['type', 'model', 'name'] as $field) {
                if (
                    property_exists($data, $field)
                    && !empty($data->$field)
                    && preg_match("/" . $regex . "/i", $data->$field)
                ) {
                    return true;
                }
            }
        }

        return false;
    }
    public function handle()
    {
        parent::handle();
        if (isset($this->harddrives)) {
            $this->harddrives->handleLinks();
            $this->harddrives->handle();
        }
    }

    public function checkConf(Conf $conf): bool
    {
        $this->conf = $conf;
        return $conf->component_drive == 1 && parent::checkConf($conf);
    }

    /**
     * Get harddrives data
     *
     * @return stdClass[]
     */
    public function getPreparedHarddrives(): array
    {
        return $this->prepared_harddrives;
    }

    public function getItemtype(): string
    {
        return Item_DeviceDrive::class;
    }
}
