<?php

/**
 * @var DBmysql $DB
 * @var Migration $migration
 */
$default_charset = DBConnection::getDefaultCharset();
$default_collation = DBConnection::getDefaultCollation();
$default_key_sign = DBConnection::getDefaultPrimaryKeySignOption();

if (!$DB->tableExists('glpi_items_processes')) {
    $query = "CREATE TABLE `glpi_items_processes` (
      `id` int {$default_key_sign} NOT NULL AUTO_INCREMENT,
      `itemtype` varchar(100) DEFAULT NULL,
      `items_id` int {$default_key_sign} NOT NULL DEFAULT '0',
      `cmd` text,
      `cpuusage` float NOT NULL DEFAULT '0',
      `memusage` float NOT NULL DEFAULT '0',
      `pid` int NOT NULL DEFAULT '1',
      `started` timestamp NULL DEFAULT NULL,
      `tty` varchar(100) DEFAULT NULL,
      `user` varchar(100) DEFAULT NULL,
      `virtualmemory` int NOT NULL DEFAULT '1',
      `is_deleted` tinyint NOT NULL DEFAULT '0',
      `is_dynamic` tinyint NOT NULL DEFAULT '0',
      PRIMARY KEY (`id`),
      KEY `item` (`itemtype`,`items_id`),
      KEY `is_deleted` (`is_deleted`),
      KEY `is_dynamic` (`is_dynamic`)
    ) ENGINE=InnoDB DEFAULT CHARSET={$default_charset} COLLATE={$default_collation} ROW_FORMAT=DYNAMIC;";
    $DB->doQuery($query);
}
