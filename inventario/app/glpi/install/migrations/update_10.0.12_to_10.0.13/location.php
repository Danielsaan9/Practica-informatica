<?php

/**
 * @var Migration $migration
 */
$migration->addField('glpi_items_devicecameras', 'locations_id', "fkey", [
    'after'  => 'is_recursive',
]);
$migration->addKey('glpi_items_devicecameras', 'locations_id');
