<?php

/**
 * @var array $ADDTODISPLAYPREF
 * @var DBmysql $DB
 * @var Migration $migration
 */
$ADDTODISPLAYPREF[KnowbaseItem::class] = [79, 131, 13];

$table = 'glpi_knowbaseitems';

$field_to_add = 'entities_id';
if (!$DB->fieldExists($table, $field_to_add)) {
    $migration->addField(
        $table,
        $field_to_add,
        'fkey',
        [
            'after' => 'id',
        ]
    );
    $migration->addKey($table, $field_to_add);
}

$field_to_add = 'is_recursive';
if (!$DB->fieldExists($table, $field_to_add)) {
    $migration->addField(
        $table,
        $field_to_add,
        'bool',
        [
            'update' => 1,
            'after' => 'entities_id',
        ]
    );
    $migration->addKey($table, $field_to_add);
}

$default_key_sign = DBConnection::getDefaultPrimaryKeySignOption();
$migration->addField('glpi_knowbaseitems', 'forms_categories_id', "int {$default_key_sign} NOT NULL DEFAULT 0", ['after' => 'entities_id']);
$migration->addField('glpi_knowbaseitems', 'description', 'longtext DEFAULT NULL', ['after' => 'view']);
$migration->addField('glpi_knowbaseitems', 'illustration', 'varchar(255) DEFAULT NULL', ['after' => 'view']);
$migration->addField('glpi_knowbaseitems', 'is_pinned', 'tinyint NOT NULL DEFAULT 0', ['after' => 'view']);
$migration->addField('glpi_knowbaseitems', 'show_in_service_catalog', 'tinyint NOT NULL DEFAULT 0', ['after' => 'view']);

$migration->addKey('glpi_knowbaseitems', 'forms_categories_id');
