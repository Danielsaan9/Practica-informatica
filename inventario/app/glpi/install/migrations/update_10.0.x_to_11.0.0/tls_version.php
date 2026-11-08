<?php

/**
 * @var DBmysql $DB
 * @var Migration $migration
 */
if (!$DB->fieldExists('glpi_authldaps', 'tls_version', false)) {
    $migration->addField('glpi_authldaps', 'tls_version', 'varchar(10) DEFAULT NULL', ['after' => 'timeout']);
}
