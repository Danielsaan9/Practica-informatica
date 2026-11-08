<?php

/**
 * @var DBmysql $DB
 * @var Migration $migration
 */

// Delete unsafe SSO variables.
$sso_variables_data = getAllDataFromTable('glpi_ssovariables');

$used_sso_variable_id = (int) ($DB->request([
    'FROM' => 'glpi_configs',
    'WHERE' => [
        'name'    => 'ssovariables_id',
        'context' => 'core',
    ],
])->current()['value'] ?? 0);

foreach ($sso_variables_data as $sso_variable_data) {
    $sso_variable_name = mb_strtoupper($sso_variable_data['name'] ?? '');
    if (
        str_starts_with($sso_variable_name, 'HTTP_') === false
        && $sso_variable_name !== 'PHP_AUTH_USER'
    ) {
        // Only headers prefixed by `HTTP_` and `PHP_AUTH_USER` are unsafe.
        continue;
    }

    if ($sso_variable_data['id'] === $used_sso_variable_id) {
        // Do not delete the variable if it is used.
        $migration->addWarningMessage(
            sprintf(
                __('The %s variable is unsafe. It should not be used for authentication.'),
                $sso_variable_name,
            )
        );
        continue;
    }

    $migration->addPostQuery($DB->buildDelete('glpi_ssovariables', ['id' => $sso_variable_data['id']]));
}
