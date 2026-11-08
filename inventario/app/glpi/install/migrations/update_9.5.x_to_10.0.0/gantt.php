<?php

/**
 * @var DBmysql $DB
 */
$default_charset = DBConnection::getDefaultCharset();
$default_collation = DBConnection::getDefaultCollation();
$default_key_sign = DBConnection::getDefaultPrimaryKeySignOption();

// Create table for project task links
if (!$DB->tableExists('glpi_projecttasklinks')) {
    $query = "CREATE TABLE `glpi_projecttasklinks` (
       `id` int {$default_key_sign} NOT NULL AUTO_INCREMENT,
       `projecttasks_id_source` int {$default_key_sign} NOT NULL,
       `source_uuid` varchar(255) NOT NULL,
       `projecttasks_id_target` int {$default_key_sign} NOT NULL,
       `target_uuid` varchar(255) NOT NULL,
       `type` tinyint NOT NULL DEFAULT '0',
       `lag` smallint DEFAULT '0',
       `lead` smallint DEFAULT '0',
       PRIMARY KEY (`id`),
       KEY `projecttasks_id_source` (`projecttasks_id_source`),
       KEY `projecttasks_id_target` (`projecttasks_id_target`)
      ) ENGINE = InnoDB ROW_FORMAT = DYNAMIC DEFAULT CHARSET = {$default_charset} COLLATE = {$default_collation};";
    $DB->doQuery($query);
}
