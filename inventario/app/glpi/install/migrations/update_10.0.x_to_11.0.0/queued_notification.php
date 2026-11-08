<?php

/**
 * @var Migration $migration
 */
// Register crontask
$migration->addCrontask(
    'QueuedNotification',
    'queuednotificationcleanstaleajax',
    DAY_TIMESTAMP,
    options: [
        'state' => 0, // CronTask::STATE_DISABLE
    ]
);

$current_config = Config::getConfigurationValues('core');
if (!isset($current_config['notifications_ajax_expiration_delay'])) {
    $migration->addConfig([
        'notifications_ajax_expiration_delay' => '30',
    ]);
}
