<?php

/**
 * @var Migration $migration
 */
$migration->addConfig([
    'password_init_token_delay'     => '86400',
    'toast_location'                => 'bottom-right',
    'set_followup_tech'             => '0',
    'set_solution_tech'             => '0',
    'is_demo_dashboards'            => '0',
    'planned_task_state'            => '1',
    'plugins_execution_mode'        => 'on', // Plugin::EXECUTION_MODE_ON
    'allow_unauthenticated_uploads' => '0',
]);
$migration->addField('glpi_users', 'toast_location', 'string');

$migration->addField('glpi_users', 'set_followup_tech', 'tinyint DEFAULT NULL');
$migration->addField('glpi_users', 'set_solution_tech', 'tinyint DEFAULT NULL');
$migration->addField(
    'glpi_users',
    'planned_task_state',
    'int DEFAULT NULL'
);

$migration->removeConfig(['url_base_api']);

// Add config entries that were missing from the default installation data since GLPI 9.5.
$migration->addConfig([
    'glpinetwork_registration_key' => null,
    'impact_assets_list' => '[]',
    'timezone' => null,
]);

$migration->addConfig([
    'enable_hlapi' => '0',
]);
