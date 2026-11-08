<?php

/**
 * @var DBmysql $DB
 * @var Migration $migration
 */
if ($DB->tableExists('glpi_networkportmigrations')) {
    $migration->dropTable('glpi_networkportmigrations');
}
