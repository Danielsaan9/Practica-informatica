<?php

/**
 * @var DBmysql $DB
 * @var Migration $migration
 */
$table = "glpi_users";
if (!$DB->fieldExists($table, "password_history")) {
    $migration->addField($table, "password_history", "longtext");
}

/**
 * Add password history config, represent the number of passwords that users
 * won't be allowed to be reuse.
 * Note that GLPI always check that a new password is different from the current
 * password so value cannot be lower than 1.
 */
$migration->addConfig(['non_reusable_passwords_count' => 1], 'core');
