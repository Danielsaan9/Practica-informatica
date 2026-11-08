<?php

/**
 * @var Migration $migration
 */
$migration->changeField('glpi_items_disks', 'totalsize', 'totalsize', 'bigint NOT NULL DEFAULT "0"');
$migration->changeField('glpi_items_disks', 'freesize', 'freesize', 'bigint NOT NULL DEFAULT "0"');
