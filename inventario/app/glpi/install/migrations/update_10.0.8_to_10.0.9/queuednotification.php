<?php

/**
 * @var DBmysql $DB
 * @var Migration $migration
 */
/* Add `event` to some glpi_queuednotifications */
/* This migration was not executed during from 10.0.7 to 10.0.8 (see #15133) */
if (!$DB->fieldExists('glpi_queuednotifications', 'event')) {
    $migration->addField('glpi_queuednotifications', 'event', 'varchar(255) DEFAULT NULL', ['value' => null]);
}
