<?php

/**
 * @var Migration $migration
 */
$default_key_sign = DBConnection::getDefaultPrimaryKeySignOption();

$migration->dropKey('glpi_items_devicecameras_imageformats', 'item_devicecameras_id');
$migration->changeField(
    'glpi_items_devicecameras_imageformats',
    'item_devicecameras_id',
    'items_devicecameras_id',
    "int {$default_key_sign} NOT NULL DEFAULT '0'"
);
$migration->addKey('glpi_items_devicecameras_imageformats', 'items_devicecameras_id');

$migration->dropKey('glpi_items_devicecameras_imageresolutions', 'item_devicecameras_id');
$migration->changeField(
    'glpi_items_devicecameras_imageresolutions',
    'item_devicecameras_id',
    'items_devicecameras_id',
    "int {$default_key_sign} NOT NULL DEFAULT '0'"
);
$migration->addKey('glpi_items_devicecameras_imageresolutions', 'items_devicecameras_id');
