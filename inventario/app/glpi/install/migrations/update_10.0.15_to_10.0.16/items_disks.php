<?php

/**
 * @var Migration $migration
 */
// same as update_10.0.14_to_10.0.15/items_disks.php
// see #16992
$migration->changeField('glpi_items_disks', 'totalsize', 'totalsize', 'bigint NOT NULL DEFAULT "0"');
$migration->changeField('glpi_items_disks', 'freesize', 'freesize', 'bigint NOT NULL DEFAULT "0"');
