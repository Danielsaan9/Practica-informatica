<?php

/**
 * @var Migration $migration
 */
// see #21349
$migration->changeField('glpi_items_processes', 'virtualmemory', 'virtualmemory', 'bigint NOT NULL DEFAULT "0"');
