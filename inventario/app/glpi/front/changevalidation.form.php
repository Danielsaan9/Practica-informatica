<?php

require_once(__DIR__ . '/_check_webserver_config.php');

/**
 * @since 0.85
 */

$validation = new ChangeValidation();

include(GLPI_ROOT . "/front/commonitilvalidation.form.php");
