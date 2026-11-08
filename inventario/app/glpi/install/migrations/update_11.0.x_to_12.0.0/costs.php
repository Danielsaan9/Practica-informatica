<?php

/**
 * @var Migration $migration
 */

$cost_tables = ['glpi_changecosts', 'glpi_contractcosts', 'glpi_problemcosts', 'glpi_projectcosts', 'glpi_ticketcosts'];

foreach ($cost_tables as $table) {
    $migration->addField($table, 'users_id', 'fkey');
    $migration->addKey($table, 'users_id');
    $migration->addField($table, 'users_id_lastupdater', 'fkey');
    $migration->addKey($table, 'users_id_lastupdater');
    $migration->addField($table, 'date_creation', 'datetime');
    $migration->addKey($table, 'date_creation');
    $migration->addField($table, 'date_mod', 'datetime');
    $migration->addKey($table, 'date_mod');
}
