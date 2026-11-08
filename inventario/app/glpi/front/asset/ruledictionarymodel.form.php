<?php

require_once(__DIR__ . '/../_check_webserver_config.php');

use Glpi\Asset\AssetDefinition;
use Glpi\Asset\RuleDictionaryModelCollection;
use Glpi\Exception\Http\BadRequestHttpException;

$definition = new AssetDefinition();
$rulecollection_class  = array_key_exists('class', $_GET) && $definition->getFromDBBySystemName((string) $_GET['class'])
    ? $definition->getAssetModelDictionaryCollectionClassName()
    : null;

if ($rulecollection_class === null || !is_a($rulecollection_class, RuleDictionaryModelCollection::class, true)) {
    throw new BadRequestHttpException('Bad request');
}

$rulecollection = new $rulecollection_class();

include(GLPI_ROOT . "/front/rule.common.form.php");
