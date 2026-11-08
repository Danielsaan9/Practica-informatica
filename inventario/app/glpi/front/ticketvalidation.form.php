<?php

require_once(__DIR__ . '/_check_webserver_config.php');

$validation = new TicketValidation();

include(GLPI_ROOT . "/front/commonitilvalidation.form.php");
