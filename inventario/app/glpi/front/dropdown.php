<?php

require_once(__DIR__ . '/_check_webserver_config.php');

use Glpi\Exception\Http\AccessDeniedHttpException;

Html::header(__('Setup'), '', "config", "commondropdown");

echo "<div class='center'>";

$optgroup = Dropdown::getStandardDropdownItemTypes();
if (count($optgroup) > 0) {
    Dropdown::showItemTypeList($optgroup);
} else {
    throw new AccessDeniedHttpException();
}

echo "</div>";
Html::footer();
