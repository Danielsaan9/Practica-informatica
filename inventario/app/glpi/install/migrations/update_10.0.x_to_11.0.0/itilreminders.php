<?php

/**
 * @var DBmysql $DB
 * @var Migration $migration
 */
$default_charset = DBConnection::getDefaultCharset();
$default_collation = DBConnection::getDefaultCollation();
$default_key_sign = DBConnection::getDefaultPrimaryKeySignOption();

// Add ITILReminder table
if (!$DB->tableExists('glpi_itilreminders')) {
    $query = "CREATE TABLE `glpi_itilreminders` (
        `id` int {$default_key_sign} NOT NULL AUTO_INCREMENT,
        `itemtype` varchar(100) NOT NULL,
        `items_id` int {$default_key_sign} NOT NULL DEFAULT '0',
        `pendingreasons_id` int {$default_key_sign} NOT NULL DEFAULT '0',
        `name` varchar(255) DEFAULT NULL,
        `content` text,
        `date_mod` timestamp NULL DEFAULT NULL,
        `date_creation` timestamp NULL DEFAULT NULL,
        PRIMARY KEY (`id`),
        KEY `item` (`itemtype`,`items_id`),
        KEY `name` (`name`),
        KEY `date_mod` (`date_mod`),
        KEY `date_creation` (`date_creation`),
        KEY `pendingreasons_id` (`pendingreasons_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=$default_charset COLLATE=$default_collation ROW_FORMAT=DYNAMIC;";
    $DB->doQuery($query);
} else {
    $migration->addField('glpi_itilreminders', 'name', 'varchar(255) DEFAULT NULL');
    $migration->addField('glpi_itilreminders', 'content', 'text');
    $migration->addKey('glpi_itilreminders', 'name', 'name');
}
