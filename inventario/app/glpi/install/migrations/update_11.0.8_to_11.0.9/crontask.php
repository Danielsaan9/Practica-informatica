<?php

/**
 * @var DBmysql $DB
 * @var Migration $migration
 */

$migration->addPostQuery(
    $DB->buildUpdate(
        'glpi_crontasks',
        ['name' => 'cleanorphansdocument'],
        ['itemtype' => 'Document', 'name' => 'cleanorphans']
    )
);

$migration->addPostQuery(
    $DB->buildUpdate(
        'glpi_crontasks',
        ['name' => 'cleanorphansinventory'],
        ['itemtype' => 'Glpi\Inventory\Inventory', 'name' => 'cleanorphans']
    )
);

$migration->addPostQuery(
    $DB->buildUpdate(
        'glpi_crontasks',
        ['name' => 'createinquestticket'],
        ['itemtype' => 'Ticket', 'name' => 'createinquest']
    )
);

$migration->addPostQuery(
    $DB->buildUpdate(
        'glpi_crontasks',
        ['name' => 'createinquestchange'],
        ['itemtype' => 'Change', 'name' => 'createinquest']
    )
);
