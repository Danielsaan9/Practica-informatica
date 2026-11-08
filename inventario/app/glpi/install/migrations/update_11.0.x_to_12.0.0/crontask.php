<?php

/**
 * @var DBmysql $DB
 * @var Migration $migration
 */

$migration->addField('glpi_crontasks', 'error_count', 'integer');
$migration->addField('glpi_crontasks', 'next_run', 'timestamp');

$migration->addCrontask(
    itemtype: 'Glpi\Config\DataAndPrivacyConfig',
    name: 'purgesessionhistory',
    frequency: DAY_TIMESTAMP,
    options: [
        'state' => 1,
        'logs_lifetime' => 30,
    ]
);
