<?php

namespace Glpi\Stat\Data\Location;

use Glpi\Stat\StatData;
use Stat;
use Toolbox;

/**
 * Data for front/stat.location.php and front/stat.specific.php
 */
abstract class StatDataLocation extends StatData
{
    public function __construct(array $params)
    {
        parent::__construct($params);

        $data_key = $this->getKey();

        $data = Stat::getData(
            $params['itemtype'],
            $params['type'],
            $params['date1'],
            $params['date2'],
            $params['start'],
            $params['val'],
            $params['value2']
        );

        if (!isset($data[$data_key]) || !is_array($data[$data_key])) {
            return;
        }

        foreach ($data[$data_key] as $key => $val) {
            if ($val > 0) {
                $newkey = Toolbox::stripTags($key);
                $this->labels[] = $newkey;
                $this->series[] = ['name' => $newkey, 'data' => $val];
                $this->total += $val;
            }
        }
    }

    abstract public function getKey(): string;
}
