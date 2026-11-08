<?php

/**
 * @var DBmysql $DB
 * @var Migration $migration
 */
$migration->addCrontask(
    'CommonITILValidationCron',
    'approvalreminder',
    1 * WEEK_TIMESTAMP,
    options: [
        'state' => 0, // CronTask::STATE_DISABLE
    ]
);

if (!$DB->fieldExists("glpi_ticketvalidations", "last_reminder_date", false)) {
    $migration->addField('glpi_ticketvalidations', 'last_reminder_date', "timestamp NULL DEFAULT NULL", ['after' => 'timeline_position' ]);
}

if (!$DB->fieldExists("glpi_changevalidations", "last_reminder_date", false)) {
    $migration->addField('glpi_changevalidations', 'last_reminder_date', "timestamp NULL DEFAULT NULL", ['after' => 'timeline_position' ]);
}

// Add approval_reminder_repeat_interval to entity
if (!$DB->fieldExists("glpi_entities", "approval_reminder_repeat_interval")) {
    $migration->addField(
        "glpi_entities",
        "approval_reminder_repeat_interval",
        "integer",
        [
            'after'     => "agent_base_url",
            'value'     => -2,               // Inherit as default value
            'update'    => '0',              // Disabled for root entity
            'condition' => 'WHERE `id` = 0',
        ]
    );
}
