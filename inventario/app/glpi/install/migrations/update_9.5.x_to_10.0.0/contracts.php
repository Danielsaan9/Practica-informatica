<?php

/**
 * @var Migration $migration
 */
$migration->changeField(Contract::getTable(), 'use_monday', 'use_sunday', 'bool');
$migration->dropKey(Contract::getTable(), 'use_monday');
$migration->changeField(Contract::getTable(), 'monday_begin_hour', 'sunday_begin_hour', 'time', [
    'value'  => '00:00:00',
]);
$migration->changeField(Contract::getTable(), 'monday_end_hour', 'sunday_end_hour', 'time', [
    'value'  => '00:00:00',
]);
$migration->migrationOneTable(Contract::getTable());
$migration->addKey(Contract::getTable(), 'use_sunday');
