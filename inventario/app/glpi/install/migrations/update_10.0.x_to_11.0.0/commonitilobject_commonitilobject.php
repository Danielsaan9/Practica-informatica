<?php

/**
 * @var DBmysql $DB
 * @var Migration $migration
 */
$to_add_link = ['glpi_changes_problems', 'glpi_changes_tickets', 'glpi_problems_tickets'];

foreach ($to_add_link as $table) {
    if (!$DB->fieldExists($table, 'link')) {
        $migration->addField($table, 'link', 'int', [
            'value' => 1,
        ]);
    }
}

$default_charset = DBConnection::getDefaultCharset();
$default_collation = DBConnection::getDefaultCollation();
$default_key_sign = DBConnection::getDefaultPrimaryKeySignOption();

// Create new tables

if (!$DB->tableExists('glpi_changes_changes')) {
    $query = "CREATE TABLE `glpi_changes_changes` (
        `id` int {$default_key_sign} NOT NULL AUTO_INCREMENT,
        `changes_id_1` int {$default_key_sign} NOT NULL DEFAULT '0',
        `changes_id_2` int {$default_key_sign} NOT NULL DEFAULT '0',
        `link` int NOT NULL DEFAULT '1',
        PRIMARY KEY (`id`),
        UNIQUE KEY `unicity` (`changes_id_1`,`changes_id_2`),
        KEY `changes_id_2` (`changes_id_2`)
        ) ENGINE=InnoDB DEFAULT CHARSET={$default_charset} COLLATE={$default_collation} ROW_FORMAT=DYNAMIC;";
    $DB->doQuery($query);
}

if (!$DB->tableExists('glpi_problems_problems')) {
    $query = "CREATE TABLE `glpi_problems_problems` (
        `id` int {$default_key_sign} NOT NULL AUTO_INCREMENT,
        `problems_id_1` int {$default_key_sign} NOT NULL DEFAULT '0',
        `problems_id_2` int {$default_key_sign} NOT NULL DEFAULT '0',
        `link` int NOT NULL DEFAULT '1',
        PRIMARY KEY (`id`),
        UNIQUE KEY `unicity` (`problems_id_1`,`problems_id_2`),
        KEY `problems_id_2` (`problems_id_2`)
        ) ENGINE=InnoDB DEFAULT CHARSET={$default_charset} COLLATE={$default_collation} ROW_FORMAT=DYNAMIC;";
    $DB->doQuery($query);
}
