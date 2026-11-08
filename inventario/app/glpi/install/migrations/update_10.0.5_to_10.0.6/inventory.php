<?php

/**
 * @var DBmysql $DB
 * @var Migration $migration
 */
$migration->addConfig(["entities_id_default" => 0], 'inventory');

$config = Config::getConfigurationValues('inventory');
if (isset($config['stale_agents_action']) && is_numeric($config['stale_agents_action'])) {
    //convert stale_agents_action to an array
    $DB->update(
        'glpi_configs',
        [
            'value' => exportArrayToDB([$config['stale_agents_action']]),
        ],
        [
            'context' => 'inventory',
            'name' => 'stale_agents_action',
        ]
    );
}
