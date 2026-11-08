<?php

require_once(__DIR__ . '/_check_webserver_config.php');

Session::checkCentralAccess();

if (isset($_GET["id"])) {
    $generic_rule = new Rule();
    $generic_rule->getFromDB($_GET["id"]);
    $generic_rule->checkGlobal(READ);

    $rulecollection = RuleCollection::getClassByType($generic_rule->fields["sub_type"]);
    include(GLPI_ROOT . "/front/rule.common.form.php");
}
