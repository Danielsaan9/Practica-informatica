<?php

require_once(__DIR__ . '/_check_webserver_config.php');

use Glpi\Application\Environment;
use Glpi\UI\ThemeManager;

use function Safe\preg_match;

if (preg_match('~^css/glpi(\.scss)?$~', $_GET['file'] ?? '') === 1) {
    // Ensure to have enough memory to not reach memory limit.
    $max_memory = Html::MAIN_SCSS_COMPILATION_REQUIRED_MEMORY;
    if (Toolbox::getMemoryLimit() < $max_memory) {
        Toolbox::safeIniSet('memory_limit', $max_memory);
    }
}

// If a custom theme is requested, we need to get the real path of the theme
if (isset($_GET['file']) && isset($_GET['is_custom_theme']) && $_GET['is_custom_theme']) {
    $theme = ThemeManager::getInstance()->getTheme($_GET['file']);

    if (!$theme) {
        trigger_error(sprintf('Unable to find theme `%s`.', $_GET['file']), E_USER_WARNING);
        $theme = ThemeManager::getInstance()->getTheme(ThemeManager::DEFAULT_THEME);
    }

    $_GET['file'] = $theme->getPath();
}

$css = Html::compileScss($_GET);

header('Content-Type: text/css');

$is_cacheable = !isset($_GET['nocache']) && Environment::get()->shouldForceExtraBrowserCache();
if ($is_cacheable) {
    // Makes CSS cacheable by browsers and proxies
    $max_age = MONTH_TIMESTAMP;
    // no `must-revalidate`, a `v=xxx` param is used to prevent extensive caching issues
    header('Cache-Control: public, max-age=' . $max_age);
    header('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', time() + $max_age));
}

echo $css;
