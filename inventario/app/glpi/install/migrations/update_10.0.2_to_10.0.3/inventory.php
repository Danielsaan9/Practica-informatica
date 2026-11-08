<?php

use Glpi\Inventory\Conf;

/**
 * @var Migration $migration
 */
//new right value for inventory
$migration->replaceRight('inventory', READ | Conf::IMPORTFROMFILE | Conf::UPDATECONFIG, ['config' => UPDATE, 'inventory' => READ]);
