<?php

/**
 * @var DBmysql $DB
 * @var Migration $migration
 */

$ip_tables = ['glpi_oauth_access_tokens', 'glpi_oauth_refresh_tokens', 'glpi_oauth_auth_codes'];
foreach ($ip_tables as $table) {
    $migration->addField($table, 'ip_address', 'VARCHAR(45) DEFAULT NULL');
}
$migration->addField('glpi_oauth_access_tokens', 'user_agent', 'VARCHAR(512) DEFAULT NULL');
