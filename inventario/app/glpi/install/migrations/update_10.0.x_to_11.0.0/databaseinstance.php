<?php

/**
 * @var DBmysql $DB
 * @var Migration $migration
 */
// The size field seems to be unsused and rather a copy-paste from the glpi_databases table
$migration->dropField('glpi_databaseinstances', 'size');
