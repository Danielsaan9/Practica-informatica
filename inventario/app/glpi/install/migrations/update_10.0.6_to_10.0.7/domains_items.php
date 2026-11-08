<?php

/**
 * @var DBmysql $DB
 * @var Migration $migration
 */
if (!$DB->fieldExists(Domain_Item::getTable(), 'is_dynamic')) {
    $migration->addField(
        Domain_Item::getTable(),
        'is_dynamic',
        'bool'
    );
    $migration->addKey(
        Domain_Item::getTable(),
        'is_dynamic'
    );

    $migration->addField(
        Domain_Item::getTable(),
        'is_deleted',
        'bool'
    );
    $migration->addKey(
        Domain_Item::getTable(),
        'is_deleted'
    );
}
