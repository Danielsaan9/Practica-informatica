<?php

require_once(__DIR__ . '/_check_webserver_config.php');

$rulecollection = new RuleDictionnaryPrinterModelCollection();

include(GLPI_ROOT . "/front/rule.common.form.php");
