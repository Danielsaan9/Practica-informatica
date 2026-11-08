<?php

/** @file
 * @brief
 */

/**
 * Update from 9.3.0 to 9.3.1
 *
 * @return bool
 **/
function update930to931()
{
    /**
     * @var Migration $migration
     */
    global $migration;

    $updateresult     = true;

    $migration->setVersion('9.3.1');

    /** Change field type */
    $migration->changeField(
        'glpi_notifications_notificationtemplates',
        'notifications_id',
        'notifications_id',
        'integer'
    );
    /** /Change field type */

    // add option to hide/show source on login page
    $migration->addConfig(['display_login_source' => 1]);

    // supplier now have use_notification = 1 by default
    $migration->changeField(
        'glpi_suppliers_tickets',
        'use_notification',
        'use_notification',
        'bool',
        [
            'value' => 1,
        ]
    );

    // ************ Keep it at the end **************
    $migration->executeMigration();

    return $updateresult;
}
