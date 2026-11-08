<?php

require_once(__DIR__ . '/_check_webserver_config.php');

use Glpi\UI\ThemeManager;

use function Safe\base64_decode;
use function Safe\filesize;
use function Safe\readfile;

$theme = ThemeManager::getInstance()->getTheme($_GET['key']);
$preview = $theme?->getPreviewPath(false);

header_remove('Pragma');
header(sprintf('Content-Disposition: attachment; filename="%s.png"', basename($theme->getKey())));
header('Content-type: image/png');

if ($preview === null) {
    header('Cache-Control: no-cache');
    // Return blank PNG to prevent "broken image" display.
    $blank = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=');
    header(sprintf('Content-Length: %s', strlen($blank)));
    echo $blank;
    return;
}

header('Cache-Control: public, max-age=2592000, must-revalidate'); // 1 month cache
header(sprintf('Content-Length: %s', filesize($preview)));
readfile($preview);
