<?php

require_once(__DIR__ . '/_check_webserver_config.php');


Session::checkCentralAccess();

Html::header(Rule::getTypeName(Session::getPluralNumber()), '', "admin", "rule");

RuleCollection::showCollectionsList();

Html::footer();
