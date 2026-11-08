<?php

/**
 * Display a massive action form
 */

global $CFG_GLPI;

header("Content-Type: text/html; charset=UTF-8");
Html::header_nocache();

try {
    $ma = new MassiveAction($_POST, $_GET, 'initial');
} catch (Throwable $e) {
    echo "<div class='center'><img src='" . htmlescape($CFG_GLPI["root_doc"]) . "/pics/warning.png' alt='"
                              . __s('Warning') . "'><br><br>";
    echo "<span class='b'>" . htmlescape($e->getMessage()) . "</span><br>";
    echo "</div>";
    return;
}

echo "<div class='center massiveactions'>";
Html::openMassiveActionsForm();
$params = ['action' => '__VALUE__'];
$input  = $ma->getInput();
foreach ($input as $key => $val) {
    $params[$key] = $val;
}

$actions = $params['actions'];

if (count($actions)) {
    if (isset($params['hidden']) && is_array($params['hidden'])) {
        foreach ($params['hidden'] as $key => $val) {
            echo Html::hidden($key, ['value' => $val]);
        }
    }
    $rand = mt_rand();

    echo "<label for=\"dropdown_massiveaction$rand\">" . _sn('Action', 'Actions', 1) . "</label>";
    echo "&nbsp;";

    $actions = ['-1' => Dropdown::EMPTY_VALUE] + $actions;
    Dropdown::showFromArray('massiveaction', $actions, ['rand' => $rand]);

    echo "<br><br>";

    Ajax::updateItemOnSelectEvent(
        "dropdown_massiveaction$rand",
        "show_massiveaction$rand",
        $CFG_GLPI["root_doc"] . "/ajax/dropdownMassiveAction.php",
        $params
    );

    echo "<span id='show_massiveaction$rand'>&nbsp;</span>\n";
}

Html::closeForm();
echo "</div>";
