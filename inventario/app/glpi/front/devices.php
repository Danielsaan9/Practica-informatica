<?php

require_once(__DIR__ . '/_check_webserver_config.php');

Session::checkRight(CommonDevice::$rightname, READ);

Html::header(_n('Component', 'Components', Session::getPluralNumber()), '', "config", "commondevice");
echo "<div class='text-center'>";

$optgroup = Dropdown::getDeviceItemTypes(true);
Dropdown::showItemTypeList($optgroup);

echo "</div>";
Html::footer();
