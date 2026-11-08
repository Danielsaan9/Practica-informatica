<?php

use Glpi\Kernel\Kernel;
use Twig\Runtime\EscaperRuntime;

use function Safe\preg_match;

/**
 * Is the script launch in Command line?
 *
 * @return bool
 */
function isCommandLine(): bool
{
    /** @var bool|null $GLPI_IS_COMMAND_LINE */
    global $GLPI_IS_COMMAND_LINE;
    return $GLPI_IS_COMMAND_LINE ?? (PHP_SAPI === 'cli');
}

/**
 * Is the script launched From API?
 *
 * @return bool
 */
function isAPI()
{
    /** @var Kernel $kernel */
    global $kernel;

    $path = $kernel->getMainRequest()->getPathInfo();

    return str_starts_with($path, '/api.php') || str_starts_with($path, '/apirest.php');
}

/**
 * Determine if an class name is a plugin one
 *
 * @param string $classname Class name to analyze
 *
 * @return bool|array False or an array containing plugin name and class name
 */
function isPluginItemType($classname)
{
    $matches = [];
    if (preg_match("/^Plugin([A-Z][a-z0-9]+)([A-Z]\w+)$/", $classname, $matches)) {
        $plug           = [];
        $plug['plugin'] = $matches[1];
        $plug['class']  = $matches[2];
        return $plug;
    } elseif (str_starts_with($classname, NS_PLUG)) {
        $tab = explode('\\', $classname, 3);
        $plug           = [];
        $plug['plugin'] = $tab[1];
        $plug['class']  = $tab[2];
        return $plug;
    }
    // Standard case
    return false;
}

/**
 * Escape a string to make it safe to be printed in an HTML page.
 * This function is pretty similar to the `htmlspecialchars` function, but its signature is less strict.
 *
 * This function will be deprecated/removed once all the HTML code of GLPI will be moved inside Twig templates.
 *
 * @param mixed $str
 * @return string
 */
function htmlescape(mixed $str): string
{
    return htmlspecialchars((string) $str);
}

/**
 * Escape a string to make it safe to be printed in a JS string variable.
 *
 * This function will be deprecated/removed once all the JS code of GLPI will be moved inside JS files or Twig templates.
 *
 * @param mixed $str
 * @return string
 */
function jsescape(mixed $str): string
{
    // Rely on the Twig escaper
    return (new EscaperRuntime())->escape((string) $str, 'js');
}
