<?php

/**
 * @var DBmysql $DB
 * @var Migration $migration
 */
$migration->giveRight('ticket', Ticket::READNEWTICKET, [
    'ticket' => Ticket::ASSIGN,
]);

if (!$DB->fieldExists('glpi_tickets', 'externalid')) {
    $migration->addField('glpi_tickets', 'externalid', 'string', [
        'null' => true,
    ]);
}
