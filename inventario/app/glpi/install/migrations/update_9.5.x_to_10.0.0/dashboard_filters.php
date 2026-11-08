<?php

/**
 * @var DBmysql $DB
 * @var Migration $migration
 */
$default_charset = DBConnection::getDefaultCharset();
$default_collation = DBConnection::getDefaultCollation();
$default_key_sign = DBConnection::getDefaultPrimaryKeySignOption();

$migration->addField('glpi_dashboards_dashboards', 'users_id', "int {$default_key_sign} NOT NULL DEFAULT '0'", ['after' => 'context']);
$migration->addKey('glpi_dashboards_dashboards', 'users_id');

if (!$DB->tableExists('glpi_dashboards_filters')) {
    $query = "CREATE TABLE `glpi_dashboards_filters` (
         `id` int {$default_key_sign} NOT NULL AUTO_INCREMENT,
         `dashboards_dashboards_id` int {$default_key_sign} NOT NULL DEFAULT '0',
         `users_id` int {$default_key_sign} NOT NULL DEFAULT '0',
         `filter` longtext,
         PRIMARY KEY (`id`),
         KEY `dashboards_dashboards_id` (`dashboards_dashboards_id`),
         KEY `users_id` (`users_id`)
      ) ENGINE = InnoDB ROW_FORMAT = DYNAMIC DEFAULT CHARSET = {$default_charset} COLLATE = {$default_collation};";
    $DB->doQuery($query);
}
