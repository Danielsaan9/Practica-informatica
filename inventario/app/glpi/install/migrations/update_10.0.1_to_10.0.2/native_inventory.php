<?php

/**
 * @var Migration $migration
 */
$migration->addCrontask(
    'Agent',
    'Cleanoldagents',
    DAY_TIMESTAMP,
    options: [
        'comment' => 'Clean old agents',
    ]
);
