<?php

/**
 * @var DBmysql $DB
 * @var Migration $migration
 */
if (!$DB->fieldExists("glpi_tickets", "takeintoaccountdate")) {
    $migration->addField("glpi_tickets", "takeintoaccountdate", "timestamp", ['null' => true,'after' => 'solvedate']);
    $migration->addKey("glpi_tickets", "takeintoaccountdate");
    $migration->migrationOneTable("glpi_tickets");
}
