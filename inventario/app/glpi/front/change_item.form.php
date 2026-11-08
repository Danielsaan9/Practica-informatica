<?php

require_once(__DIR__ . '/_check_webserver_config.php');

$obj      = new Change();
$item_obj = new Change_Item();
include(GLPI_ROOT . '/front/commonitilobject_item.form.php');
