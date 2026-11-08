<?php

require_once(__DIR__ . '/_check_webserver_config.php');

$obj      = new TicketRecurrent();
$item_obj = new Item_TicketRecurrent();
include(GLPI_ROOT . '/front/commonitilobject_item.form.php');
