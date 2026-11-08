<?php

use Glpi\Asset\AssetDefinition;

use function Safe\json_encode;

header("Content-Type: application/json; charset=UTF-8");
Html::header_nocache();
Session::writeClose();

echo json_encode(AssetDefinition::getPluralFormsForLanguage($_GET['language']));
