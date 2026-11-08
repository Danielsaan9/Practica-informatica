<?php

/**
 * @var Migration $migration
 * @var array $ADDTODISPLAYPREF
 * @var DBmysql $DB
 */
$default_charset = DBConnection::getDefaultCharset();
$default_collation = DBConnection::getDefaultCollation();

if (!$DB->tableExists('glpi_contracts_users')) {
    $query = "CREATE TABLE `glpi_contracts_users` (
        `id` int unsigned NOT NULL AUTO_INCREMENT,
        `contracts_id` int unsigned NOT NULL DEFAULT '0',
        `users_id` int unsigned NOT NULL DEFAULT '0',
        PRIMARY KEY (`id`),
        UNIQUE KEY `unicity` (`contracts_id`,`users_id`),
        KEY `item` (`users_id`)
      ) ENGINE=InnoDB DEFAULT CHARSET = {$default_charset} COLLATE = {$default_collation} ROW_FORMAT=DYNAMIC";
    $DB->doQuery($query);
}
