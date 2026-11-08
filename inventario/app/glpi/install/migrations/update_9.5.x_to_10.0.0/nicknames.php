<?php

/**
 * @var DBmysql $DB
 * @var Migration $migration
 */
if (!$DB->fieldExists("glpi_users", "nickname")) {
    $migration->addField("glpi_users", "nickname", "string");
}
