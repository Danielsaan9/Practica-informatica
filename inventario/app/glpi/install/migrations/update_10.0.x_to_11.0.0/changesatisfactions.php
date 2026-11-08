<?php

use Glpi\DBAL\QueryExpression;

/**
 * @var DBmysql $DB
 * @var Migration $migration
 */
$default_charset = DBConnection::getDefaultCharset();
$default_collation = DBConnection::getDefaultCollation();
$default_key_sign = DBConnection::getDefaultPrimaryKeySignOption();

if (!$DB->tableExists('glpi_changesatisfactions')) {
    $query = "CREATE TABLE `glpi_changesatisfactions` (
        `id` int {$default_key_sign} NOT NULL AUTO_INCREMENT,
        `changes_id` int {$default_key_sign} NOT NULL DEFAULT '0',
        `type` int NOT NULL DEFAULT '1',
        `date_begin` timestamp NULL DEFAULT NULL,
        `date_answered` timestamp NULL DEFAULT NULL,
        `satisfaction` int DEFAULT NULL,
        `comment` text,
        PRIMARY KEY (`id`),
        UNIQUE KEY `changes_id` (`changes_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET={$default_charset} COLLATE={$default_collation} ROW_FORMAT=DYNAMIC;";
    $DB->doQuery($query);
}

// Register crontask
$migration->addCrontask(
    'Change',
    'createinquest',
    DAY_TIMESTAMP
);

// Add new entity config columns
if (!$DB->fieldExists('glpi_entities', 'max_closedate_change')) {
    $migration->addField('glpi_entities', 'max_closedate_change', 'timestamp', [
        'after' => 'inquest_URL',
        'null'  => true,
    ]);
}
if (!$DB->fieldExists('glpi_entities', 'inquest_config_change')) {
    $migration->addField('glpi_entities', 'inquest_config_change', 'integer', [
        'after'     => 'max_closedate_change',
        'value'     => -2,
        // Internal survey for root entity
        'update'    => '1',
        'condition' => 'WHERE `id` = 0',
    ]);
}
if (!$DB->fieldExists('glpi_entities', 'inquest_rate_change')) {
    $migration->addField('glpi_entities', 'inquest_rate_change', 'integer', [
        'after' => 'inquest_config_change',
        'value' => 0,
    ]);
}
if (!$DB->fieldExists('glpi_entities', 'inquest_delay_change')) {
    $migration->addField('glpi_entities', 'inquest_delay_change', 'integer', [
        'after'     => 'inquest_rate_change',
        'value'     => -10,
        // Unlimited for root entity
        'update'    => '0',
        'condition' => 'WHERE `id` = 0',
    ]);
}
if (!$DB->fieldExists('glpi_entities', 'inquest_URL_change')) {
    $migration->addField('glpi_entities', 'inquest_URL_change', 'string', [
        'after' => 'inquest_delay_change',
        'null'  => true,
    ]);
}
if (!$DB->fieldExists('glpi_entities', 'inquest_max_rate_change')) {
    $migration->addField('glpi_entities', 'inquest_max_rate_change', 'integer', [
        'after' => 'inquest_URL_change',
        'value' => 5,
    ]);
}
if (!$DB->fieldExists('glpi_entities', 'inquest_default_rate_change')) {
    $migration->addField('glpi_entities', 'inquest_default_rate_change', 'integer', [
        'after' => 'inquest_max_rate_change',
        'value' => 3,
    ]);
}
if (!$DB->fieldExists('glpi_entities', 'inquest_mandatory_comment_change')) {
    $migration->addField('glpi_entities', 'inquest_mandatory_comment_change', 'integer', [
        'after' => 'inquest_default_rate_change',
        'value' => 0,
    ]);
}
if (!$DB->fieldExists('glpi_entities', 'inquest_duration_change')) {
    $migration->addField('glpi_entities', 'inquest_duration_change', 'integer', [
        'after' => 'inquest_duration',
        'value' => 0,
    ]);
}

$migration->giveRight('change', CommonITILObject::SURVEY, [
    'change' => Change::READMY,
]);

// Replace old TICKETCATEGORY tags in Entity inquest_URL field with ITILCATEGORY
$DB->update(
    'glpi_entities',
    [
        'inquest_URL' => new QueryExpression(
            'REPLACE(inquest_URL, \'[TICKETCATEGORY_\', \'##[ITILCATEGORY_\')'
        ),
    ],
    [
        'inquest_URL' => ['LIKE', '%[TICKETCATEGORY_%'],
    ]
);

// Keep track of satisfaction on a fixed scale (for stats)
foreach (['glpi_changesatisfactions', 'glpi_ticketsatisfactions'] as $table) {
    if (!$DB->fieldExists($table, 'satisfaction_scaled_to_5')) {
        $migration->addField($table, 'satisfaction_scaled_to_5', 'float DEFAULT NULL', [
            'after' => 'satisfaction',
        ]);
        $migration->addPostQuery(
            $DB->buildUpdate($table, [
                'satisfaction_scaled_to_5' => new QueryExpression($DB->quoteName('satisfaction')),
            ], [new QueryExpression('true')])
        );
    }
}
