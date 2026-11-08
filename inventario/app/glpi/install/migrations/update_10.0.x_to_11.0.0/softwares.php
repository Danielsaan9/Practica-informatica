<?php

/**
 * @var DBmysql $DB
 * @var Migration $migration
 */
// PurgeSoftwareTask cron task
$migration->addCrontask(
    'Software',
    'purgesoftware',
    MONTH_TIMESTAMP,
    param: 1000,
    options: [
        'state' => 0, // CronTask::STATE_DISABLE
        'logs_lifetime' => 300,
    ]
);
