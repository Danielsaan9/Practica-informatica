<?php

/**
 * @var Migration $migration
 */

$auth_configured = countElementsInTable(
    'glpi_configs',
    [
        'context' => 'inventory',
        'name'    => 'auth_required',
        'NOT'     => ['value' => ''],
    ]
);

if ($auth_configured === 0) {
    $migration->addWarningMessage(
        __('No authentication method is configured for inventory: agents will be rejected until one is selected in the inventory configuration.')
    );
}

$migration->addConfig(
    [
        'auth_required'       => '',
        'basic_auth_login'    => '',
        'basic_auth_password' => '',
    ],
    'inventory'
);
