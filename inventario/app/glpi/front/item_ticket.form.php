<?php

require_once(__DIR__ . '/_check_webserver_config.php');

$obj      = new Ticket();
$item_obj = new Item_Ticket();
include(GLPI_ROOT . '/front/commonitilobject_item.form.php');
