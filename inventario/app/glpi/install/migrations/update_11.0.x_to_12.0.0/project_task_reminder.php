<?php

use Glpi\DBAL\QueryExpression;

/**
 * @var DBmysql $DB
 * @var Migration $migration
 */

$migration->addField('glpi_projecttasks', 'recall', 'integer', ['nodefault' => true, 'null' => true]);

if (!countElementsInTable('glpi_crontasks', ['itemtype' => 'ProjectTask', 'name' => 'projecttasksreminder'])) {
    $DB->insert(
        'glpi_crontasks',
        [
            'itemtype'      => 'ProjectTask',
            'name'          => 'projecttasksreminder',
            'frequency'     => HOUR_TIMESTAMP,
            'param'         => 3,
            'state'         => CronTask::STATE_WAITING,
            'mode'          => CronTask::MODE_INTERNAL,
            'allowmode'     => CronTask::MODE_INTERNAL | CronTask::MODE_EXTERNAL,
            'logs_lifetime' => 30,
            'lastrun'       => null,
            'comment'       => '',
            'hourmin'       => 0,
            'hourmax'       => 24,
        ]
    );
}

if (!countElementsInTable('glpi_notifications', ['itemtype' => 'ProjectTask', 'event' => 'planningrecall'])) {
    $DB->insert(
        'glpi_notifications',
        [
            'name'          => 'Project Task Planning Reminder',
            'entities_id'   => 0,
            'itemtype'      => 'ProjectTask',
            'event'         => 'planningrecall',
            'comment'       => '',
            'is_recursive'  => 1,
            'is_active'     => 1,
            'date_creation' => new QueryExpression('NOW()'),
            'date_mod'      => new QueryExpression('NOW()'),
        ]
    );
    $notifications_id = $DB->insertId();

    $template = $DB->request([
        'SELECT' => ['id'],
        'FROM'   => 'glpi_notificationtemplates',
        'WHERE'  => ['itemtype' => 'ProjectTask'],
        'LIMIT'  => 1,
    ])->current();

    if ($template) {
        $DB->insert(
            'glpi_notifications_notificationtemplates',
            [
                'notifications_id'         => $notifications_id,
                'mode'                     => Notification_NotificationTemplate::MODE_MAIL,
                'notificationtemplates_id' => $template['id'],
            ]
        );
    }

    foreach ([Notification::TEAM_USER, Notification::AUTHOR, Notification::TEAM_GROUP] as $items_id) {
        $DB->insert(
            'glpi_notificationtargets',
            [
                'items_id'         => $items_id,
                'type'             => Notification::USER_TYPE,
                'notifications_id' => $notifications_id,
            ]
        );
    }
}
