<?php

/**
 * @var DBmysql $DB
 * @var Migration $migration
 */

if (!$DB->tableExists('glpi_knowbaseitems_favorites')) {
    $DB->doQuery("CREATE TABLE `glpi_knowbaseitems_favorites` (
        `id`               int unsigned NOT NULL AUTO_INCREMENT,
        `knowbaseitems_id` int unsigned NOT NULL DEFAULT '0',
        `users_id`         int unsigned NOT NULL DEFAULT '0',
        PRIMARY KEY (`id`),
        UNIQUE KEY `unicity` (`knowbaseitems_id`,`users_id`),
        KEY `users_id` (`users_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;");
}
