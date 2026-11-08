<?php

/**
 * @var array $ADDTODISPLAYPREF
 * @var DBmysql $DB
 * @var Migration $migration
 */

$default_charset = DBConnection::getDefaultCharset();
$default_collation = DBConnection::getDefaultCollation();
$default_key_sign = DBConnection::getDefaultPrimaryKeySignOption();

if (!$DB->tableExists('glpi_dropdowns_dropdowndefinitions')) {
    $query = <<<SQL
        CREATE TABLE `glpi_dropdowns_dropdowndefinitions` (
            `id` int {$default_key_sign} NOT NULL AUTO_INCREMENT,
            `system_name` varchar(255) DEFAULT NULL,
            `label` varchar(255) NOT NULL,
            `icon` varchar(255) DEFAULT NULL,
            `comment` text,
            `is_active` tinyint NOT NULL DEFAULT '0',
            `profiles` JSON NOT NULL,
            `translations` JSON NOT NULL,
            `date_creation` timestamp NULL DEFAULT NULL,
            `date_mod` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`),
            UNIQUE KEY `system_name` (`system_name`),
            KEY `is_active` (`is_active`),
            KEY `label` (`label`),
            KEY `date_creation` (`date_creation`),
            KEY `date_mod` (`date_mod`)
    ) ENGINE=InnoDB DEFAULT CHARSET={$default_charset} COLLATE={$default_collation} ROW_FORMAT=DYNAMIC;
SQL;
    $DB->doQuery($query);
} else {
    $migration->addField('glpi_dropdowns_dropdowndefinitions', 'label', 'string', [
        'after' => 'system_name',
        'update' => $DB::quoteName('system_name'),
    ]);
    $migration->addKey('glpi_dropdowns_dropdowndefinitions', 'label');

    // Add `Dropdown` suffix to custom asset classes.
    $definitions_iterator = $DB->request(['FROM' => 'glpi_dropdowns_dropdowndefinitions']);
    foreach ($definitions_iterator as $definition_data) {
        $migration->renameItemtype(
            'Glpi\\CustomDropdown\\' . $definition_data['system_name'],
            'Glpi\\CustomDropdown\\' . $definition_data['system_name'] . 'Dropdown',
            false
        );
    }
}

if (!$DB->tableExists('glpi_dropdowns_dropdowns')) {
    $query = <<<SQL
        CREATE TABLE `glpi_dropdowns_dropdowns` (
            `id` int {$default_key_sign} NOT NULL AUTO_INCREMENT,
            `dropdowns_dropdowndefinitions_id` int {$default_key_sign} NOT NULL,
            `name` varchar(255) DEFAULT NULL,
            `comment` text,
            `entities_id` int {$default_key_sign} NOT NULL DEFAULT '0',
            `is_recursive` tinyint NOT NULL DEFAULT '0',
            `dropdowns_dropdowns_id` int {$default_key_sign} NOT NULL DEFAULT '0',
            `completename` text,
            `level` int NOT NULL DEFAULT '0',
            `ancestors_cache` longtext,
            `sons_cache` longtext,
            `date_creation` timestamp NULL DEFAULT NULL,
            `date_mod` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`),
            KEY `dropdowns_dropdowndefinitions_id` (`dropdowns_dropdowndefinitions_id`),
            KEY `name` (`name`),
            KEY `entities_id` (`entities_id`),
            KEY `is_recursive` (`is_recursive`),
            KEY `dropdowns_dropdowns_id` (`dropdowns_dropdowns_id`),
            KEY `level` (`level`),
            KEY `date_creation` (`date_creation`),
            KEY `date_mod` (`date_mod`)
        ) ENGINE=InnoDB DEFAULT CHARSET={$default_charset} COLLATE={$default_collation} ROW_FORMAT=DYNAMIC;
SQL;
    $DB->doQuery($query);
} else {
    $migration->dropField('glpi_dropdowns_dropdowns', 'is_deleted');
}
