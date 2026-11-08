<?php

use Glpi\DBAL\QueryExpression;

/**
 * @var DBmysql $DB
 * @var Migration $migration
 */

/** User mention notification */
$notification_exists = countElementsInTable('glpi_notifications', ['itemtype' => 'Ticket', 'event' => 'user_mention']) > 0;
if (!$notification_exists) {
    $DB->insert(
        'glpi_notifications',
        [
            'id'              => null,
            'name'            => 'New user mentioned',
            'entities_id'     => 0,
            'itemtype'        => 'Ticket',
            'event'           => 'user_mention',
            'comment'         => '',
            'is_recursive'    => 1,
            'is_active'       => 1,
            'date_creation'   => new QueryExpression('NOW()'),
            'date_mod'        => new QueryExpression('NOW()'),
        ]
    );
    $notification_id = $DB->insertId();

    $notificationtemplate = new NotificationTemplate();
    if ($notificationtemplate->getFromDBByCrit(['name' => 'Tickets', 'itemtype' => 'Ticket'])) {
        $DB->insert(
            'glpi_notifications_notificationtemplates',
            [
                'notifications_id'         => $notification_id,
                'mode'                     => Notification_NotificationTemplate::MODE_MAIL,
                'notificationtemplates_id' => $notificationtemplate->fields['id'],
            ]
        );
    }

    $DB->insert(
        'glpi_notificationtargets',
        [
            'items_id'         => '39',
            'type'             => '1',
            'notifications_id' => $notification_id,
        ]
    );
}
/** /User mention notification */
