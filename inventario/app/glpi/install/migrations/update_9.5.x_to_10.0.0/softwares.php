<?php

/**
 * @var DBmysql $DB
 * @var Migration $migration
 */
// CleanSoftwareCron cron task
$migration->addCrontask(
    'CleanSoftwareCron',
    'cleansoftware',
    MONTH_TIMESTAMP,
    param: 1000,
    options: [
        'state' => 0, // CronTask::STATE_DISABLE
        'logs_lifetime' => 300,
    ]
);
// /CleanSoftwareCron cron task

// Add architecture to software versions
if (!$DB->fieldExists('glpi_softwareversions', 'arch', false)) {
    $migration->addField(
        'glpi_softwareversions',
        'arch',
        'string',
        [
            'after' => 'name',
        ]
    );
    $migration->addKey('glpi_softwareversions', 'arch');
}
// /Add architecture to software versions
