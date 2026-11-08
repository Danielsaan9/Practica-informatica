<?php

/**
 * @var DBmysql $DB
 * @var Migration $migration
 */
$default_charset = DBConnection::getDefaultCharset();
$default_collation = DBConnection::getDefaultCollation();
$default_key_sign = DBConnection::getDefaultPrimaryKeySignOption();

if (!$DB->tableExists('glpi_oauth_access_tokens')) {
    $query = "CREATE TABLE `glpi_oauth_access_tokens` (
        `identifier` varchar(255) NOT NULL,
        `client` varchar(255) NOT NULL,
        `date_expiration` timestamp NOT NULL,
        `user_identifier` varchar(255) DEFAULT NULL,
        `scopes` text DEFAULT NULL,
        PRIMARY KEY (`identifier`),
        KEY `client` (`client`)
        ) ENGINE=InnoDB DEFAULT CHARSET={$default_charset} COLLATE={$default_collation} ROW_FORMAT=DYNAMIC";
    $DB->doQuery($query);
}

if (!$DB->tableExists('glpi_oauth_auth_codes')) {
    $query = "CREATE TABLE `glpi_oauth_auth_codes` (
        `identifier` varchar(255) NOT NULL,
        `client` varchar(255) NOT NULL,
        `date_expiration` timestamp NOT NULL,
        `user_identifier` varchar(255) DEFAULT NULL,
        `scopes` text DEFAULT NULL,
        PRIMARY KEY (`identifier`),
        KEY `client` (`client`)
        ) ENGINE=InnoDB DEFAULT CHARSET={$default_charset} COLLATE={$default_collation} ROW_FORMAT=DYNAMIC";
    $DB->doQuery($query);
}

if (!$DB->tableExists('glpi_oauth_refresh_tokens')) {
    $query = "CREATE TABLE `glpi_oauth_refresh_tokens` (
        `identifier` varchar(255) NOT NULL,
        `access_token` varchar(255) NOT NULL,
        `date_expiration` timestamp NOT NULL,
        PRIMARY KEY (`identifier`),
        KEY `access_token` (`access_token`)
        ) ENGINE=InnoDB DEFAULT CHARSET={$default_charset} COLLATE={$default_collation} ROW_FORMAT=DYNAMIC";
    $DB->doQuery($query);
}

if (!$DB->tableExists('glpi_oauthclients')) {
    $query = "CREATE TABLE `glpi_oauthclients` (
        `identifier` varchar(255) NOT NULL,
        `id` int {$default_key_sign} NOT NULL AUTO_INCREMENT COMMENT 'Internal GLPI ID',
        `name` varchar(255) NOT NULL DEFAULT '',
        `comment` text DEFAULT NULL,
        `secret` varchar(255) NOT NULL,
        `redirect_uri` TEXT NOT NULL,
        `grants` text NOT NULL,
        `scopes` text NOT NULL,
        `is_active` tinyint NOT NULL DEFAULT '1',
        `is_confidential` tinyint NOT NULL DEFAULT '1',
        `allowed_ips` text DEFAULT NULL,
        PRIMARY KEY (`identifier`),
        KEY `id` (`id`),
        KEY `name` (`name`),
        KEY `is_active` (`is_active`)
        ) ENGINE=InnoDB DEFAULT CHARSET={$default_charset} COLLATE={$default_collation} ROW_FORMAT=DYNAMIC";
    $DB->doQuery($query);
} else {
    // Dev migration for `redirect_uri` column from varchar(255) to TEXT
    $migration->changeField('glpi_oauthclients', 'redirect_uri', 'redirect_uri', 'TEXT NOT NULL');

    $migration->addField('glpi_oauthclients', 'allowed_ips', 'TEXT DEFAULT NULL', [
        'after' => 'is_confidential',
    ]);
}

$migration->addRight('oauth_client', ALLSTANDARDRIGHT, ['config' => UPDATE]);
