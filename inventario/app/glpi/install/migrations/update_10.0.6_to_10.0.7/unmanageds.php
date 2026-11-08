<?php

/**
 * @var DBmysql $DB
 * @var Migration $migration
 */
$default_key_sign = DBConnection::getDefaultPrimaryKeySignOption();

if ($DB->fieldExists(Unmanaged::getTable(), 'domains_id')) {
    $iterator = $DB->request([
        'SELECT' => ['id', 'domains_id'],
        'FROM'   => Unmanaged::getTable(),
        'WHERE'  => ['domains_id' => ['>', 0]],
    ]);
    if (count($iterator)) {
        foreach ($iterator as $row) {
            $DB->insert("glpi_domains_items", [
                'domains_id'   => $row['domains_id'],
                'itemtype'     => 'Unmanaged',
                'items_id'     => $row['id'],
            ]);
        }
    }
    $migration->dropField(Unmanaged::getTable(), 'domains_id');
}

if (!$DB->fieldExists(Unmanaged::getTable(), 'users_id_tech')) {
    $migration->addField(Unmanaged::getTable(), 'users_id_tech', "int {$default_key_sign} NOT NULL DEFAULT '0'", ['after' => 'states_id']);
    $migration->addKey(Unmanaged::getTable(), 'users_id_tech');
}

//new right value for unmanageds (previously based on config UPDATE)
$migration->addRight('unmanaged', READ | UPDATE | DELETE | PURGE, ['config' => UPDATE]);
