<?php

/**
 * @var Migration $migration
 */
$migration->addField('glpi_printerlogs', 'itemtype', 'varchar(100) NOT NULL', ['after' => 'id']);
$migration->changeField('glpi_printerlogs', 'printers_id', 'items_id', 'fkey');
$migration->dropKey('glpi_printerlogs', 'unicity');
$migration->migrationOneTable('glpi_printerlogs');
$migration->addKey('glpi_printerlogs', ['itemtype', 'items_id', 'date'], 'unicity', 'UNIQUE');
