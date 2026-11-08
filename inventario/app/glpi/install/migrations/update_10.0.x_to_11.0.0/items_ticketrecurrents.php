<?php

/**
 * @var DBmysql $DB
 * @var Migration $migration
 */
$default_charset = DBConnection::getDefaultCharset();
$default_collation = DBConnection::getDefaultCollation();
$default_key_sign = DBConnection::getDefaultPrimaryKeySignOption();

// Change Ticket recurent items
// Add glpi_items_ticketrecurrents table for associated elements
if (!$DB->tableExists('glpi_items_ticketrecurrents')) {
    $query = "CREATE TABLE `glpi_items_ticketrecurrents` (
        `id` int {$default_key_sign} NOT NULL AUTO_INCREMENT,
        `itemtype` varchar(255) DEFAULT NULL,
        `items_id` int {$default_key_sign} NOT NULL DEFAULT '0',
        `ticketrecurrents_id` int {$default_key_sign} NOT NULL DEFAULT '0',
        PRIMARY KEY (`id`),
        UNIQUE KEY `unicity` (`itemtype`,`items_id`,`ticketrecurrents_id`),
        KEY `items_id` (`items_id`),
        KEY `ticketrecurrents_id` (`ticketrecurrents_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET={$default_charset} COLLATE={$default_collation} ROW_FORMAT=DYNAMIC;";
    $DB->doQuery($query);
}

// Add glpi_items_ticketrecurrents table for associated elements
$migration->addField('glpi_ticketrecurrents', 'ticket_per_item', 'bool');
$migration->addKey('glpi_ticketrecurrents', 'ticket_per_item');
