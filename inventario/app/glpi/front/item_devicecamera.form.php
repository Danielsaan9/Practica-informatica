<?php

require_once(__DIR__ . '/_check_webserver_config.php');

$item_device = new Item_DeviceCamera();
include(GLPI_ROOT . "/front/item_device.common.form.php");
