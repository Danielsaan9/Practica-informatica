<?php

use function Safe\spl_autoload_register;

/**
 * Classes loader
 *
 * @param string $classname
 * @return void
 */
function glpi_autoload($classname)
{
    if (!str_starts_with($classname, 'Plugin') && !str_starts_with($classname, NS_PLUG)) {
        return;
    }

    $plug = isPluginItemType($classname);
    if (!$plug) {
        return;
    }

    $plugin_key   = strtolower($plug['plugin']);
    $plugin_class = $plug['class'];

    if (!Plugin::isPluginLoaded($plugin_key)) {
        return;
    }

    $plugin_path = null;
    foreach (GLPI_PLUGINS_DIRECTORIES as $plugins_dir) {
        $dir_to_check = sprintf('%s/%s', $plugins_dir, $plugin_key);
        if (is_dir($dir_to_check)) {
            $plugin_path = $dir_to_check;
            break;
        }
    }

    /**
     * Legacy class path, e.g. `PluginMyPluginFoo` -> `plugins/myplugin/inc/foo.class.php`.
     *
     * PHP files inside the `inc` directory are safe for inclusion.
     * @psalm-taint-escape include
     */
    $legacy_path      = sprintf('%s/inc/%s.class.php', $plugin_path, str_replace('\\', '/', strtolower($plugin_class)));
    if (file_exists($legacy_path)) {
        include_once($legacy_path);
        return;
    }

    /**
     * PSR-4 styled path for class without namespace, e.g. `PluginMyPluginFoo` -> `plugins/myplugin/src/PluginMyPluginFoo.php`
     *
     * PHP files inside the `src` directory are safe for inclusion.
     * @psalm-taint-escape include
     */
    $psr4_styled_path = sprintf('%s/src/%s.php', $plugin_path, str_replace('\\', '/', $classname));
    if (file_exists($psr4_styled_path)) {
        include_once($psr4_styled_path);
        return;
    }
}

spl_autoload_register('glpi_autoload');
