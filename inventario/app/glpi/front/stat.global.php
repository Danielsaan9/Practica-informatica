<?php

require_once(__DIR__ . '/_check_webserver_config.php');

use Glpi\Exception\Http\BadRequestHttpException;
use Glpi\Stat\Data\Sglobal\StatDataAverageSatisfaction;
use Glpi\Stat\Data\Sglobal\StatDataSatisfaction;
use Glpi\Stat\Data\Sglobal\StatDataTicketAverageTime;
use Glpi\Stat\Data\Sglobal\StatDataTicketNumber;

use function Safe\mktime;
use function Safe\preg_match;

Html::header(__('Statistics'), '', "helpdesk", "stat");

Session::checkRight(Stat::$rightname, READ);

//sanitize dates
foreach (['date1', 'date2'] as $key) {
    if (array_key_exists($key, $_GET) && preg_match('/^\d{4}-\d{2}-\d{2}$/', (string) $_GET[$key]) !== 1) {
        unset($_GET[$key]);
    }
}
if (empty($_GET["date1"]) && empty($_GET["date2"])) {
    $year          = date("Y") - 1;
    $_GET["date1"] = date("Y-m-d", mktime(1, 0, 0, (int) date("m"), (int) date("d"), $year));
    $_GET["date2"] = date("Y-m-d");
}

if (
    !empty($_GET["date1"])
    && !empty($_GET["date2"])
    && (strcmp($_GET["date2"], $_GET["date1"]) < 0)
) {
    $tmp           = $_GET["date1"];
    $_GET["date1"] = $_GET["date2"];
    $_GET["date2"] = $tmp;
}

Stat::title();

if (!$item = getItemForItemtype($_GET['itemtype'])) {
    throw new BadRequestHttpException();
}

$stat = new Stat();

$stat->displaySearchForm(
    $_GET['itemtype'],
    $_GET['date1'],
    $_GET['date2']
);

$stat_params = [
    'itemtype' => $_GET['itemtype'],
    'date1'    => $_GET['date1'],
    'date2'    => $_GET['date2'],
];

echo "<div class='text-center mt-3'>";
$stat->displayLineGraphFromData(new StatDataTicketNumber($stat_params));
$stat->displayLineGraphFromData(new StatDataTicketAverageTime($stat_params));

if ($_GET['itemtype'] == 'Ticket') {
    $stat->displayLineGraphFromData(new StatDataSatisfaction($stat_params));
    $stat->displayLineGraphFromData(new StatDataAverageSatisfaction($stat_params));
}
echo "</div>";

Html::footer();
