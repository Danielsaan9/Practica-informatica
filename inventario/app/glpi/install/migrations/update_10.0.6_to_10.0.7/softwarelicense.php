<?php

/**
 * @var DBmysql $DB
 * @var Migration $migration
 */
if (!$DB->fieldExists('glpi_softwarelicenses', 'ancestors_cache')) {
    $migration->addField('glpi_softwarelicenses', 'ancestors_cache', 'longtext');
}

if (!$DB->fieldExists('glpi_softwarelicenses', 'sons_cache')) {
    $migration->addField('glpi_softwarelicenses', 'sons_cache', 'longtext');
}
