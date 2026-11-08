<?php

/**
 * @var DBmysql $DB
 * @var Migration $migration
 */
if (!$DB->fieldExists("glpi_solutiontypes", "is_incident")) {
    $migration->addField('glpi_solutiontypes', 'is_incident', 'bool', ['value' => 1]);
    $migration->addKey('glpi_solutiontypes', 'is_incident');
}
if (!$DB->fieldExists("glpi_solutiontypes", "is_request")) {
    $migration->addField('glpi_solutiontypes', 'is_request', 'bool', ['value' => 1]);
    $migration->addKey('glpi_solutiontypes', 'is_request');
}
if (!$DB->fieldExists("glpi_solutiontypes", "is_change")) {
    $migration->addField('glpi_solutiontypes', 'is_change', 'bool', ['value' => 1]);
    $migration->addKey('glpi_solutiontypes', 'is_change');
}
if (!$DB->fieldExists("glpi_solutiontypes", "is_problem")) {
    $migration->addField('glpi_solutiontypes', 'is_problem', 'bool', ['value' => 1]);
    $migration->addKey('glpi_solutiontypes', 'is_problem');
}
