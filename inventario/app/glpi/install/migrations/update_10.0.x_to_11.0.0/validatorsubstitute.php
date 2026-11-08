<?php

/**
 * @var DBmysql $DB
 * @var Migration $migration
 */
$default_charset = DBConnection::getDefaultCharset();
$default_collation = DBConnection::getDefaultCollation();
$default_key_sign = DBConnection::getDefaultPrimaryKeySignOption();

if (!$DB->tableExists('glpi_validatorsubstitutes')) {
    $query = "CREATE TABLE `glpi_validatorsubstitutes` (
        `id` int {$default_key_sign} NOT NULL AUTO_INCREMENT,
        `users_id` int {$default_key_sign}  NOT NULL DEFAULT '0' COMMENT 'Delegator user',
        `users_id_substitute` int {$default_key_sign}  NOT NULL DEFAULT '0' COMMENT 'Substitute user',
        PRIMARY KEY (`id`),
        UNIQUE KEY `users_id_users_id_substitute` (`users_id`, `users_id_substitute`),
        KEY `users_id_substitute` (`users_id_substitute`)
    ) ENGINE = InnoDB ROW_FORMAT = DYNAMIC DEFAULT CHARSET = {$default_charset} COLLATE = {$default_collation};";
    $DB->doQuery($query);
}

$table = 'glpi_users';
$migration->addField($table, 'substitution_start_date', 'timestamp', ['after' => 'nickname']);
$migration->addField($table, 'substitution_end_date', 'timestamp', ['after' => 'substitution_start_date']);
$migration->addKey($table, 'substitution_end_date');
$migration->addKey($table, 'substitution_start_date');
