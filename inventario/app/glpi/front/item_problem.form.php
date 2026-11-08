<?php

require_once(__DIR__ . '/_check_webserver_config.php');

$obj      = new Problem();
$item_obj = new Item_Problem();
include(GLPI_ROOT . '/front/commonitilobject_item.form.php');
