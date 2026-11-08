<?php

require_once(__DIR__ . '/_check_webserver_config.php');

Html::popHeader(__('Setup'));

$params = Search::manageParams('DocumentType', $_GET);

Search::showList('DocumentType', $params);

Html::popFooter();
