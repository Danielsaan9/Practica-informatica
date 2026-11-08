<?php

require_once(__DIR__ . '/_check_webserver_config.php');

$task = new TicketTask();
include(GLPI_ROOT . "/front/commonitiltask.form.php");
