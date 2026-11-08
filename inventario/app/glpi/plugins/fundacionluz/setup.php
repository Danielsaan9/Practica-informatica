<?php

function plugin_init_fundacionluz(): void
{
    global $PLUGIN_HOOKS;

    $PLUGIN_HOOKS['csrf_compliant']['fundacionluz'] = true;
    if (!Plugin::isPluginActive('fundacionluz')) {
        return;
    }

    require_once __DIR__ . '/inc/valuation.class.php';
    $PLUGIN_HOOKS['menu_toadd']['fundacionluz'] = ['assets' => PluginFundacionluzValuation::class];
    $PLUGIN_HOOKS['config_page']['fundacionluz'] = 'front/config.form.php';
    Plugin::registerClass(PluginFundacionluzValuation::class, ['addtabon' => [Computer::class]]);
}

function plugin_version_fundacionluz(): array
{
    return [
        'name'         => 'Fundación Luz',
        'version'      => '1.0.0',
        'author'       => 'Fundación Luz',
        'requirements' => [
            'glpi' => ['min' => '12.0.0', 'max' => '12.0.99'],
        ],
    ];
}

function plugin_fundacionluz_check_prerequisites(): bool
{
    return version_compare(GLPI_VERSION, '12.0.0', '>=')
        && version_compare(GLPI_VERSION, '12.1.0', '<');
}

function plugin_fundacionluz_install(): bool
{
    global $DB;

    return $DB->doQuery("CREATE TABLE IF NOT EXISTS `glpi_plugin_fundacionluz_valuations` (
        `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
        `itemtype` VARCHAR(64) NOT NULL,
        `models_id` INT UNSIGNED NOT NULL,
        `replacement_value` DECIMAL(15,2) NOT NULL DEFAULT 0,
        `source` VARCHAR(255) NOT NULL,
        `estimate_date` DATE NOT NULL,
        `users_id` INT UNSIGNED NOT NULL DEFAULT 0,
        `date_mod` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        UNIQUE KEY `item_model` (`itemtype`, `models_id`),
        KEY `estimate_date` (`estimate_date`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
}

function plugin_fundacionluz_uninstall(): bool
{
    return true;
}
