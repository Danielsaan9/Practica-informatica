<?php

require_once(__DIR__ . '/_check_webserver_config.php');

use Glpi\Application\View\TemplateRenderer;

use function Safe\mktime;

Html::header(__('Statistics'), '', "helpdesk", "stat");

Session::checkRight(Stat::$rightname, READ);

if (isset($_GET["date1"])) {
    $_POST["date1"] = $_GET["date1"];
}
if (isset($_GET["date2"])) {
    $_POST["date2"] = $_GET["date2"];
}

if (empty($_POST["date1"]) && empty($_POST["date2"])) {
    $year           = ((int) date("Y")) - 1;
    $_POST["date1"] = date("Y-m-d", mktime(1, 0, 0, (int) date("m"), (int) date("d"), $year));
    $_POST["date2"] = date("Y-m-d");
}

if (
    !empty($_POST["date1"])
    && !empty($_POST["date2"])
    && (strcmp($_POST["date2"], $_POST["date1"]) < 0)
) {
    $tmp            = $_POST["date1"];
    $_POST["date1"] = $_POST["date2"];
    $_POST["date2"] = $tmp;
}

if (!isset($_GET["start"])) {
    $_GET["start"] = 0;
}

Stat::title();

TemplateRenderer::getInstance()->display('pages/assistance/stats/form.html.twig', [
    'target'    => 'stat.item.php',
    'itemtype'  => $_GET['itemtype'],
    'date1'     => $_POST["date1"],
    'date2'     => $_POST["date2"],
]);

Stat::showItems('stat.item.php', $_POST["date1"], $_POST["date2"], $_GET['start'], $_GET["itemtype"] ?? 'Ticket');

Html::footer();
