<?php

use Glpi\Socket;

require_once(__DIR__ . '/_check_webserver_config.php');

Session::checkRight(Report::$rightname, READ);

Html::header(Report::getTypeName(Session::getPluralNumber()), '', "tools", "report");

$itemtype = match (true) {
    isset($_GET['locations_id']) => Location::class,
    isset($_GET['switch']) => NetworkEquipment::class,
    isset($_GET['prise']) => Socket::class,
    default => null
};
$items_id = $_GET['locations_id'] ?? $_GET['switch'] ?? $_GET['prise'] ?? 0;
Report::title();
Report::showNetworkReport($itemtype, $items_id);
Html::footer();
