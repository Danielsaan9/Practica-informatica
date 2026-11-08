<?php

/**
 * @var Migration $migration
 */
// Add index on level on all TreeDropdown tables
$tables = [
    'glpi_businesscriticities',
    'glpi_documentcategories',
    'glpi_entities',
    'glpi_groups',
    'glpi_ipnetworks',
    'glpi_itilcategories',
    'glpi_knowbaseitemcategories',
    'glpi_locations',
    'glpi_softwarecategories',
    'glpi_softwarelicenses',
    'glpi_softwarelicensetypes',
    'glpi_states',
    'glpi_taskcategories',
];

foreach ($tables as $table) {
    $migration->addKey($table, 'level');
}
