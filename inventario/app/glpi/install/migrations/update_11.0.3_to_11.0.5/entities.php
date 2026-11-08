<?php

/**
 * @var DBmysql $DB
 * @var Migration $migration
 */
// see #18814
$migration->changeField('glpi_entities', 'inquest_URL', 'inquest_URL', 'text');
$migration->changeField('glpi_entities', 'inquest_URL_change', 'inquest_URL_change', 'text');

// Fix glpi_entities.id column to use AUTO_INCREMENT instead of DEFAULT 0
// This is required for concurrent entity creation to work properly
// see #22625
// Add NO_AUTO_VALUE_ON_ZERO to allow operations on entities with id=0 (root entity).
// Use IF() to handle the case where @@sql_mode is empty, as CONCAT would produce a
// malformed string starting with a comma, causing the SET SESSION to fail silently.
$DB->doQuery("SET SESSION sql_mode = IF(@@sql_mode = '', 'NO_AUTO_VALUE_ON_ZERO', CONCAT(@@sql_mode, ',NO_AUTO_VALUE_ON_ZERO'))");
$DB->doQuery(
    "ALTER TABLE `glpi_entities`
     MODIFY `id` INT unsigned NOT NULL AUTO_INCREMENT"
);

// Reset AUTO_INCREMENT to continue from the highest existing ID
$max_id = $DB->request([
    'SELECT' => ['MAX' => 'id AS max_id'],
    'FROM'   => 'glpi_entities',
])->current()['max_id'] ?? 0;
$DB->doQuery("ALTER TABLE `glpi_entities` AUTO_INCREMENT = " . ($max_id + 1));
