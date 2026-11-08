<?php

use Glpi\DBAL\QueryExpression;

/**
 * @var DBmysql $DB
 * @var Migration $migration
 */
// Add a rule to refuse emails that corresponds to a GLPI notification
if (countElementsInTable('glpi_rules', ['uuid' => 'glpi_rule_mail_collector_glpi_notifications']) === 0) {
    // Add the missing rule
    $migration->createRule(
        [
            'name'          => 'GLPI notifications',
            'description'   => 'Exclude emails corresponding to a GLPI notification',
            'uuid'          => 'glpi_rule_mail_collector_glpi_notifications',
            'match'         => 'AND',
            'sub_type'      => 'RuleMailCollector',
            'is_active'     => 1,
            'entities_id'   => 0,
            'is_recursive'  => 1,
            'condition'     => 0,
        ],
        [
            [
                'criteria'  => 'message_id',
                'condition' => 6,
                'pattern'   => '/GLPI(_(?<uuid>[a-z0-9]+))?(-(?<itemtype>[a-z]+))?(-(?<items_id>[0-9]+))?(\/(?<event>[a-z_]+))?(\.(?<random>[0-9]+\.[0-9]+))?@(?<uname>.+)/i',
            ],
        ],
        [
            [
                'field'         => '_refuse_email_no_response',
                'action_type'  => "assign",
                'value'         => 1,
            ],
        ]
    );

    // Move all rules to an higher ranking and put the new rule to first position
    $migration->addPostQuery(
        $DB->buildUpdate(
            'glpi_rules',
            [
                'ranking'   => new QueryExpression($DB::quoteName('ranking') . ' + 1'),
            ],
            [
                'sub_type'  => 'RuleMailCollector',
            ]
        )
    );
    $migration->addPostQuery(
        $DB->buildUpdate(
            'glpi_rules',
            [
                'ranking'   => 1,
            ],
            [
                'uuid'      => 'glpi_rule_mail_collector_glpi_notifications',
            ]
        )
    );
}
