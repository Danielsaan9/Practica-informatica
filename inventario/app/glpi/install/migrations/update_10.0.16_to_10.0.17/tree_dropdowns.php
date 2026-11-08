<?php

use Glpi\DBAL\QueryExpression;

/**
 * @var DBmysql $DB
 * @var Migration $migration
 */
// Drop the ancestors/sons cache that may have been corrupted by bugs that have now been resolved.
$tree_dropdown_tables = [
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
foreach ($tree_dropdown_tables as $table) {
    $migration->addPostQuery(
        $DB->buildUpdate(
            $table,
            [
                'ancestors_cache' => null,
                'sons_cache' => null,
            ],
            [
                new QueryExpression('true'),
            ]
        )
    );
}
