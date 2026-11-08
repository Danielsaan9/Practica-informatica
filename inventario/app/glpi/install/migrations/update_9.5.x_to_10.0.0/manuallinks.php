<?php

/**
 * @var DBmysql $DB
 */
$default_charset = DBConnection::getDefaultCharset();
$default_collation = DBConnection::getDefaultCollation();
$default_key_sign = DBConnection::getDefaultPrimaryKeySignOption();

if (!$DB->tableExists('glpi_manuallinks')) {
    $query = "CREATE TABLE `glpi_manuallinks` (
      `id` int {$default_key_sign} NOT NULL AUTO_INCREMENT,
      `name` varchar(255) DEFAULT NULL,
      `url` varchar(8096) NOT NULL,
      `open_window` tinyint NOT NULL DEFAULT '1',
      `icon` varchar(255) DEFAULT NULL,
      `comment` text,
      `items_id` int {$default_key_sign} NOT NULL DEFAULT '0',
      `itemtype` varchar(255) DEFAULT NULL,
      `date_creation` timestamp NULL DEFAULT NULL,
      `date_mod` timestamp NULL DEFAULT NULL,
      PRIMARY KEY (`id`),
      KEY `item` (`itemtype`,`items_id`),
      KEY `items_id` (`items_id`),
      KEY `date_creation` (`date_creation`),
      KEY `date_mod` (`date_mod`)
      ) ENGINE = InnoDB ROW_FORMAT = DYNAMIC DEFAULT CHARSET = {$default_charset} COLLATE = {$default_collation};";
    $DB->doQuery($query);
}
