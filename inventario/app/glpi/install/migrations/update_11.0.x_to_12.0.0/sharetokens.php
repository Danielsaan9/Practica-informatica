<?php

/**
 * @var DBmysql $DB
 * @var Migration $migration
 */
$default_charset = DBConnection::getDefaultCharset();
$default_collation = DBConnection::getDefaultCollation();
$default_key_sign = DBConnection::getDefaultPrimaryKeySignOption();

if (!$DB->tableExists('glpi_sharetokens')) {
    $query = "CREATE TABLE `glpi_sharetokens` (
        `id` int {$default_key_sign} NOT NULL AUTO_INCREMENT,
        `itemtype` varchar(255) NOT NULL,
        `items_id` int {$default_key_sign} NOT NULL DEFAULT '0',
        `name` varchar(255) DEFAULT NULL,
        `token` varchar(255) NOT NULL,
        `token_hint` varchar(16) NOT NULL DEFAULT '',
        `is_active` tinyint NOT NULL DEFAULT '1',
        `users_id` int {$default_key_sign} NOT NULL DEFAULT '0',
        `date_creation` timestamp NULL DEFAULT NULL,
        `date_mod` timestamp NULL DEFAULT NULL,
        `date_expiration` timestamp NULL DEFAULT NULL,
        PRIMARY KEY (`id`),
        UNIQUE KEY `token` (`token`),
        KEY `token_hint` (`token_hint`),
        KEY `item` (`itemtype`, `items_id`),
        KEY `name` (`name`),
        KEY `is_active` (`is_active`),
        KEY `users_id` (`users_id`),
        KEY `date_creation` (`date_creation`),
        KEY `date_mod` (`date_mod`),
        KEY `date_expiration` (`date_expiration`)
    ) ENGINE=InnoDB DEFAULT CHARSET={$default_charset} COLLATE={$default_collation} ROW_FORMAT=DYNAMIC;";
    $DB->doQuery($query);
}
