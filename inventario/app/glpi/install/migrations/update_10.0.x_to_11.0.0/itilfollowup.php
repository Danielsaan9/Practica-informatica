<?php

/**
 * @var Migration $migration
 */
// new right values for task
$migration->giveRight(
    'followup',
    // ITILFollowup::ADD_AS_TECHNICIAN,
    32768,
    [
        'ticket' => 32768, // Ticket::OWN
    ]
);
