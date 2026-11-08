<?php

require_once(__DIR__ . '/_check_webserver_config.php');

/**
 * @since 0.85
 */

$item_device = new Item_DeviceMemory();
include(GLPI_ROOT . "/front/item_device.common.form.php");
