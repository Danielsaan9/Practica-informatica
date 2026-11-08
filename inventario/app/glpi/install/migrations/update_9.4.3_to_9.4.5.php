<?php

/**
 * Update from 9.4.3 to 9.4.5
 *
 * @return bool
 **/
function update943to945()
{
    /**
     * @var DBmysql $DB
     * @var Migration $migration
     */
    global $DB, $migration;

    $updateresult     = true;

    $migration->setVersion('9.4.5');

    /** Add OLA TTR begin date field to Tickets */
    $migration->addField(
        'glpi_tickets',
        'ola_ttr_begin_date',
        'datetime',
        [
            'after'     => 'olalevels_id_ttr',
            'update'    => $DB->quoteName('date'), // Assign ticket creation date by default
            'condition' => sprintf('WHERE NOT(%s = %d)', $DB->quoteName('olas_id_ttr'), 0),
        ]
    );
    /** /Add OLA TTR begin date field to Tickets */

    /** Fix language fields */
    $translatable_tables = [
        'glpi_dropdowntranslations'             => 'DEFAULT NULL',
        'glpi_knowbaseitemtranslations'         => 'DEFAULT NULL',
        'glpi_notificationtemplatetranslations' => "NOT NULL DEFAULT ''",
        'glpi_knowbaseitems_revisions'          => 'DEFAULT NULL',
        'glpi_knowbaseitems_comments'           => 'DEFAULT NULL',
    ];
    foreach ($translatable_tables as $table => $default) {
        $migration->changeField(
            $table,
            'language',
            'language',
            'varchar(10) COLLATE utf8_unicode_ci ' . $default
        );
        $migration->addPostQuery(
            $DB->buildUpdate(
                $table,
                ['language' => 'es_419'],
                ['language' => 'es_41']
            )
        );
    }
    /** /Fix language fields */

    // ************ Keep it at the end **************
    $migration->executeMigration();

    return $updateresult;
}
