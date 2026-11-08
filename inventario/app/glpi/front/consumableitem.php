<?php

require_once(__DIR__ . '/_check_webserver_config.php');

Session::checkRightsOr(Consumable::$rightname, [READ, READ_ASSIGNED, READ_OWNED]);

Html::header(Consumable::getTypeName(Session::getPluralNumber()), '', "assets", "consumableitem");

if (isset($_GET["synthese"])) {
    Consumable::showSummary();
} else {
    Search::show('ConsumableItem');
}

Html::footer();
