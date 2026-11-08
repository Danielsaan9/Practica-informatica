<?php

/**
 * @var Migration $migration
 */
// new right values for task
$migration->giveRight(
    'task',
    //CommonITILTask::ADDMY | CommonITILTask::ADD_AS_GROUP | CommonITILTask::ADD_AS_OBSERVER | CommonITILTask::ADD_AS_TECHNICIAN,
    4 | 2048 | 16384 | 32768,
    [
        'ticket' => 32768, // Ticket::OWN
    ]
);
