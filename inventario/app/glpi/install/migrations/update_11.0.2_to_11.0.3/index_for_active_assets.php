<?php

/**
 * @var Migration $migration
 */

$assets_tables_with_templates_and_trashbin = [
    'glpi_budgets',
    'glpi_certificates',
    'glpi_computers',
    'glpi_contracts',
    'glpi_domains',
    'glpi_cables',
    'glpi_monitors',
    'glpi_networkequipments',
    'glpi_passivedcequipments',
    'glpi_peripherals',
    'glpi_phones',
    'glpi_printers',
    'glpi_projects',
    'glpi_projecttasks',
    'glpi_softwarelicenses',
    'glpi_softwares',
    'glpi_racks',
    'glpi_enclosures',
    'glpi_pdus',
    'glpi_assets_assets',
];

foreach ($assets_tables_with_templates_and_trashbin as $table) {
    $migration->addKey($table, ['is_deleted', 'is_template'], 'active_assets');
    $migration->dropKey($table, 'is_deleted');
}
