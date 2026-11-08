<?php

/**
 * @var Migration $migration
 */
$migration->addField('glpi_cartridgeitems', 'stock_target', 'int', [
    'value'  => 0,
    'after'  => 'alarm_threshold',
]);

$migration->addField('glpi_consumableitems', 'stock_target', 'int', [
    'value'  => 0,
    'after'  => 'alarm_threshold',
]);
