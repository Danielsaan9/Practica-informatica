<?php

use Glpi\DBAL\QueryExpression;
use Glpi\DBAL\QuerySubQuery;

/**
 * Update from 9.5.5 to 9.5.6
 *
 * @return bool
 **/
function update955to956()
{
    /**
     * @var array $CFG_GLPI
     * @var DBmysql $DB
     * @var Migration $migration
     */
    global $DB, $migration, $CFG_GLPI;

    $updateresult     = true;

    $migration->setVersion('9.5.6');

    // Change DC itemtype template_name search option ID from 50 to 61 to prevent duplicate IDs now that those itemtypes have Infocom search options.
    $migration->changeSearchOption(Enclosure::class, 50, 61);
    $migration->changeSearchOption(PassiveDCEquipment::class, 50, 61);
    $migration->changeSearchOption(PDU::class, 50, 61);
    $migration->changeSearchOption(Rack::class, 50, 61);

    /* Add `date` to some glpi_documents_items */
    if (!$DB->fieldExists('glpi_documents_items', 'date')) {
        $migration->addField('glpi_documents_items', 'date', 'timestamp');
        $migration->addKey('glpi_documents_items', 'date');

        // Init date from the parent followup
        $parent_date = new QuerySubQuery([
            'SELECT' => 'date',
            'FROM' => 'glpi_itilfollowups',
            'WHERE' => [
                'id' => new QueryExpression($DB->quoteName('glpi_documents_items.items_id')),
            ],
        ]);

        $migration->addPostQuery(
            $DB->buildUpdate(
                'glpi_documents_items',
                ['date' => new QueryExpression($parent_date->getQuery())],
                ['itemtype' => ['ITILFollowup']]
            )
        );

        // Init date as the value of date_creation for others items
        $migration->addPostQuery(
            $DB->buildUpdate(
                'glpi_documents_items',
                ['date' => new QueryExpression($DB->quoteName('glpi_documents_items.date_creation'))],
                ['itemtype' => ['!=', 'ITILFollowup']]
            )
        );
    }
    /* /Add `date` to glpi_documents_items */

    // ************ Keep it at the end **************
    $migration->executeMigration();

    return $updateresult;
}
