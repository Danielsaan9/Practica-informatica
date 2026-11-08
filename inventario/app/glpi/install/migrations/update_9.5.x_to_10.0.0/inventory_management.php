<?php

/**
 * @var DBmysql $DB
 */
$default_charset = DBConnection::getDefaultCharset();
$default_collation = DBConnection::getDefaultCollation();
$default_key_sign = DBConnection::getDefaultPrimaryKeySignOption();

if (!$DB->tableExists('glpi_items_remotemanagements')) {
    $query = "CREATE TABLE `glpi_items_remotemanagements` (
         `id` int {$default_key_sign} NOT NULL AUTO_INCREMENT,
         `itemtype` varchar(100) DEFAULT NULL,
         `items_id` int {$default_key_sign} NOT NULL DEFAULT '0',
         `remoteid` varchar(255) DEFAULT NULL,
         `type` varchar(255) DEFAULT NULL,
         `is_dynamic` tinyint NOT NULL DEFAULT '0',
         PRIMARY KEY (`id`),
         KEY `is_dynamic` (`is_dynamic`),
         KEY `item` (`itemtype`,`items_id`)
      ) ENGINE = InnoDB ROW_FORMAT = DYNAMIC DEFAULT CHARSET = {$default_charset} COLLATE = {$default_collation};";
    $DB->doQuery($query);
}
