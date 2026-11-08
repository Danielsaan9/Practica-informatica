<?php

/**
 * @var DBmysql $DB
 * @var Migration $migration
 */
$default_charset = DBConnection::getDefaultCharset();
$default_collation = DBConnection::getDefaultCollation();
$default_key_sign = DBConnection::getDefaultPrimaryKeySignOption();

$table = "glpi_searches_criteriafilters";
if (!$DB->tableExists($table)) {
    $query = "CREATE TABLE `$table` (
        `id` int {$default_key_sign} NOT NULL AUTO_INCREMENT,
        `itemtype` varchar(100) DEFAULT NULL,
        `items_id` int {$default_key_sign} NOT NULL DEFAULT '0',
        `search_itemtype` varchar(255) DEFAULT NULL,
        `search_criteria` longtext DEFAULT NULL,
        PRIMARY KEY (`id`),
        UNIQUE KEY `item` (`itemtype`, `items_id`),
        KEY `search_itemtype` (`search_itemtype`)
    ) ENGINE=InnoDB DEFAULT CHARSET={$default_charset} COLLATE={$default_collation} ROW_FORMAT=DYNAMIC;";

    $DB->doQuery($query);
}

$table = "glpi_defaultfilters";
if (!$DB->tableExists($table)) {
    $query = "CREATE TABLE `$table` (
        `id` int {$default_key_sign} NOT NULL AUTO_INCREMENT,
        `name` varchar(255) DEFAULT NULL,
        `is_active` tinyint NOT NULL DEFAULT '1',
        `comment` text DEFAULT NULL,
        `itemtype` varchar(100) DEFAULT NULL,
        PRIMARY KEY (`id`),
        UNIQUE KEY `itemtype` (`itemtype`),
        KEY `name` (`name`),
        KEY `is_active` (`is_active`)
    ) ENGINE=InnoDB DEFAULT CHARSET={$default_charset} COLLATE={$default_collation} ROW_FORMAT=DYNAMIC;";

    $DB->doQuery($query);
}

$migration->addRight(DefaultFilter::$rightname, ALLSTANDARDRIGHT, ['config' => UPDATE]);
