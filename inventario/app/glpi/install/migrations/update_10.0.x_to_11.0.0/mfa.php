<?php

/**
 * @var DBmysql $DB
 * @var Migration $migration
 */
if (!$DB->fieldExists('glpi_users', '2fa')) {
    $migration->addField('glpi_users', '2fa', 'text');
}
if (!$DB->fieldExists('glpi_users', '2fa_unenforced')) {
    $migration->addField('glpi_users', '2fa_unenforced', 'bool');
}
if (!$DB->fieldExists('glpi_entities', '2fa_enforcement_strategy')) {
    $migration->addField('glpi_entities', '2fa_enforcement_strategy', 'tinyint NOT NULL DEFAULT -2');
    // Root entity should have this set to 0 by default
    $migration->addPostQuery(
        $DB->buildUpdate(
            'glpi_entities',
            ['2fa_enforcement_strategy' => 0],
            ['id' => 0]
        )
    );
}
if (!$DB->fieldExists('glpi_profiles', '2fa_enforced')) {
    $migration->addField('glpi_profiles', '2fa_enforced', 'bool');
}
if (!$DB->fieldExists('glpi_groups', '2fa_enforced')) {
    $migration->addField('glpi_groups', '2fa_enforced', 'bool');
}
$migration->addConfig([
    '2fa_enforced' => 0,
    '2fa_grace_date_start' => null,
    '2fa_grace_days' => 0,
    '2fa_suffix' => '',
]);
