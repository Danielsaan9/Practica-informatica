<?php

/**
 * @var DBmysql $DB
 * @var Migration $migration
 */

if (!$DB->fieldExists('glpi_entities', 'service_catalog_default_sort_strategy')) {
    $migration->addField(
        'glpi_entities',
        'service_catalog_default_sort_strategy',
        "varchar(255) NOT NULL DEFAULT '-2'"
    );
    $migration->addPostQuery(
        $DB->buildUpdate(
            'glpi_entities',
            ['service_catalog_default_sort_strategy' => 'popularity'],
            ['id' => 0]
        )
    );
}
