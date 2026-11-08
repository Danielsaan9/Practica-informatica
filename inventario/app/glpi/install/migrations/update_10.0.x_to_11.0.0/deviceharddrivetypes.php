<?php

/**
 * @var DBmysql $DB
 * @var Migration $migration
 */
$default_charset = DBConnection::getDefaultCharset();
$default_collation = DBConnection::getDefaultCollation();
$default_key_sign = DBConnection::getDefaultPrimaryKeySignOption();

$migration->addField('glpi_deviceharddrives', 'deviceharddrivetypes_id', 'fkey');
$migration->addKey('glpi_deviceharddrives', 'deviceharddrivetypes_id', "deviceharddrivetypes_id");

if (!$DB->tableExists('glpi_deviceharddrivetypes')) {
    $query = "CREATE TABLE `glpi_deviceharddrivetypes` (
        `id` int {$default_key_sign} NOT NULL AUTO_INCREMENT,
        `name` varchar(255) DEFAULT NULL,
        `comment` text,
        PRIMARY KEY (`id`),
        KEY `name` (`name`)
      ) ENGINE=InnoDB DEFAULT CHARSET={$default_charset} COLLATE={$default_collation} ROW_FORMAT=DYNAMIC";
    $DB->doQuery($query);
}
