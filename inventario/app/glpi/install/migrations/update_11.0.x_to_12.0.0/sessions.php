<?php

/**
 * @var Migration $migration
 * @var DBmysql $DB
 */

if (!$DB->tableExists('glpi_usertokens')) {
    $DB->doQuery(<<<SQL
        CREATE TABLE `glpi_usertokens` (
            `id` int unsigned NOT NULL AUTO_INCREMENT,
            `users_id` int unsigned NOT NULL,
            `type` varchar(64) NOT NULL,
            `token_uid` char(16) NOT NULL,
            `token_hash` varchar(255) NOT NULL,
            `date_expiration` timestamp NOT NULL,
            PRIMARY KEY (`id`),
            UNIQUE KEY `token_uid` (`token_uid`),
            KEY `users_id` (`users_id`),
            KEY `type` (`type`),
            KEY `date_expiration` (`date_expiration`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;
SQL);
}

if (!$DB->tableExists('glpi_users_sessions')) {
    $DB->doQuery(<<<SQL
        CREATE TABLE `glpi_users_sessions` (
            `id` int unsigned NOT NULL AUTO_INCREMENT,
            `users_id` int unsigned NOT NULL,
            `login_session_uid` varchar(64) NOT NULL,
            `session_file` varchar(261) NOT NULL COMMENT 'Current session filename. PHP allows up to 256 characters for session IDs + the "sess_" prefix used by default.',
            `ip_address` varchar(45) NOT NULL,
            `user_agent` varchar(512) NOT NULL,
            `auth_type` tinyint NOT NULL,
            `created_at` timestamp NOT NULL,
            `last_activity_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            UNIQUE KEY `login_session_uid` (`login_session_uid`),
            KEY `users_id` (`users_id`),
            KEY `last_activity_at` (`last_activity_at`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;
SQL);
}

if (!$DB->tableExists('glpi_users_sessionhistories')) {
    $DB->doQuery(<<<SQL
        CREATE TABLE `glpi_users_sessionhistories` (
            `id` int unsigned NOT NULL AUTO_INCREMENT,
            `users_id` int unsigned NOT NULL,
            `login_session_uid` varchar(64) NOT NULL,
            `ip_address` varchar(45) NOT NULL,
            `user_agent` varchar(512) NOT NULL,
            `auth_type` tinyint NOT NULL,
            `logged_in_at` timestamp NOT NULL,
            `logged_out_at` timestamp NULL DEFAULT NULL,
            `logout_reason` enum('user', 'admin', 'expired') DEFAULT NULL,
            `users_id_revoked_by` int unsigned DEFAULT NULL,
            PRIMARY KEY (`id`),
            KEY `users_id` (`users_id`, `logged_in_at` DESC),
            KEY `users_id_revoked_by` (`users_id_revoked_by`),
            KEY `logged_out_at` (`logged_out_at`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;
SQL);
}

$migration->dropField('glpi_users', 'cookie_token');
$migration->dropField('glpi_users', 'cookie_token_date');

if (!$DB->fieldExists('glpi_oauth_access_tokens', 'uuid')) {
    $DB->doQuery("DELETE FROM `glpi_oauth_access_tokens` WHERE true");
    $migration->addField('glpi_oauth_access_tokens', 'uuid', 'varchar(255) NOT NULL', ['after' => 'identifier']);
    $migration->addKey('glpi_oauth_access_tokens', 'uuid', 'uuid', 'UNIQUE');
}

$migration->addConfig([
    'login_history_retention_days' => 90,
], 'core');
