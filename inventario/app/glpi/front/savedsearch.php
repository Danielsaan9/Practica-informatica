<?php

require_once(__DIR__ . '/_check_webserver_config.php');

if (Session::getCurrentInterface() == "helpdesk") {
    Html::helpHeader(SavedSearch::getTypeName(Session::getPluralNumber()));
} else {
    Html::header(SavedSearch::getTypeName(Session::getPluralNumber()), '', 'tools', 'savedsearch');
}

$savedsearch = new SavedSearch();

if (
    isset($_GET['action']) && $_GET["action"] == "load"
    && isset($_GET["id"]) && ($_GET["id"] > 0)
) {
    $savedsearch->check($_GET["id"], READ);
    $savedsearch->load($_GET["id"]);
    return;
}

Search::show('SavedSearch');
Html::footer();
