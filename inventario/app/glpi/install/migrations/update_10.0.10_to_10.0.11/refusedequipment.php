<?php

/**
 * @var Migration $migration
 */
$migration->changeField(
    RefusedEquipment::getTable(),
    'ip',
    'ip',
    "text"
);

$migration->changeField(
    RefusedEquipment::getTable(),
    'mac',
    'mac',
    "text"
);
