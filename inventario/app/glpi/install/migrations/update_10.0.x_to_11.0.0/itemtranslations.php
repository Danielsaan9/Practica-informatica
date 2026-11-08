<?php

/**
 * @var DBmysql $DB
 * @var Migration $migration
 */

$default_charset   = DBConnection::getDefaultCharset();
$default_collation = DBConnection::getDefaultCollation();
$default_key_sign  = DBConnection::getDefaultPrimaryKeySignOption();

if (!$DB->tableExists('glpi_itemtranslations_itemtranslations')) {
    $DB->doQuery(
        "CREATE TABLE `glpi_itemtranslations_itemtranslations` (
            `id` int {$default_key_sign} NOT NULL AUTO_INCREMENT,
            `items_id` int unsigned NOT NULL DEFAULT '0',
            `itemtype` varchar(100) NOT NULL,
            `key` varchar(255) NOT NULL,
            `language` varchar(10) NOT NULL,
            `translations` JSON NOT NULL,
            `hash` varchar(32) DEFAULT NULL,
            PRIMARY KEY (`id`),
            UNIQUE KEY `unique_item_key` (`items_id`, `itemtype`, `key`, `language`),
            KEY `item` (`itemtype`, `items_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET={$default_charset} COLLATE={$default_collation} ROW_FORMAT=DYNAMIC;"
    );
} else {
    $migration->changeField('glpi_itemtranslations_itemtranslations', 'language', 'language', 'varchar(10) NOT NULL');
}
