<?php

use function Safe\json_encode;

global $CFG_GLPI, $GLPI_CACHE;

header("Content-Type: application/json; charset=UTF-8");
Html::header_nocache();

echo json_encode(Entity::getEntitySelectorTree());
return;
