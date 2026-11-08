<?php

/**
 * @var Migration $migration
 */
$table = "glpi_authldaps";
$migration->addField($table, "begin_date_field", "string");
$migration->addField($table, "end_date_field", "string");

$migration->dropField($table, 'entity_field');
$migration->dropField($table, 'entity_condition');
