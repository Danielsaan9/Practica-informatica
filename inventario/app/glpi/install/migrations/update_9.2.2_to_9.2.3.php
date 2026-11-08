<?php

/** @file
 * @brief
 */

/**
 * Update from 9.2.2 to 9.2.3
 *
 * @return bool
 **/
function update922to923()
{
    /**
     * @var DBmysql $DB
     * @var Migration $migration
     */
    global $DB, $migration;

    $updateresult     = true;

    $migration->setVersion('9.2.3');

    //add a column for the model
    if (!$DB->fieldExists("glpi_devicepcis", "devicenetworkcardmodels_id")) {
        $migration->addField(
            "glpi_devicepcis",
            "devicenetworkcardmodels_id",
            "int NOT NULL DEFAULT '0'",
            ['after' => 'manufacturers_id']
        );
        $migration->addKey('glpi_devicepcis', 'devicenetworkcardmodels_id');
    }

    //fix notificationtemplates_id in translations table
    $notifs = [
        'Certificate',
        'SavedSearch_Alert',
    ];
    foreach ($notifs as $notif) {
        $notification = new Notification();
        $template = new NotificationTemplate();

        if (
            $notification->getFromDBByCrit(['itemtype' => $notif, 'event' => 'alert'])
            && $template->getFromDBByCrit(['itemtype' => $notif])
        ) {
            $DB->update(
                "glpi_notificationtemplatetranslations",
                ["notificationtemplates_id" => $template->fields['id']],
                ["notificationtemplates_id" => $notification->fields['id']]
            );

            if (
                $notif == 'SavedSearch_Alert'
                && countElementsInTable(
                    'glpi_notifications_notificationtemplates',
                    [
                        'notifications_id'            =>  $notification->fields['id'],
                        'notificationtemplates_id'    => $template->fields['id'],
                        'mode'                        => Notification_NotificationTemplate::MODE_MAIL,
                    ]
                ) == 0
            ) {
                //Add missing notification template link for saved searches
                $DB->insert("glpi_notifications_notificationtemplates", [
                    'notifications_id'         => $notification->fields['id'],
                    'mode'                     => Notification_NotificationTemplate::MODE_MAIL,
                    'notificationtemplates_id' => $template->fields['id'],
                ]);
            }
        }
    }

    // ************ Keep it at the end **************
    $migration->executeMigration();

    return $updateresult;
}
