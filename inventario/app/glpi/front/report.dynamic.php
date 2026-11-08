<?php

require_once(__DIR__ . '/_check_webserver_config.php');

use Glpi\Exception\Http\AccessDeniedHttpException;
use Glpi\Plugin\Hooks;

if (!isset($_GET['item_type']) || !is_string($_GET['item_type']) || !is_a($_GET['item_type'], CommonGLPI::class, true)) {
    return;
}

/** @var class-string<AllAssets|CommonDBTM|Stat> $itemtype */
$itemtype = $_GET['item_type'];
$item = getItemForItemtype($itemtype);
if (!$item) {
    throw new AccessDeniedHttpException();
}
if ($item instanceof AllAssets) {
    Session::checkCentralAccess();
} elseif ($item instanceof CommonDBTM) {
    $item->checkGlobal(READ);
} elseif (!$item::canView()) {
    throw new AccessDeniedHttpException();
}

if (isset($_GET["display_type"])) {
    if ($_GET["display_type"] < 0) {
        $_GET["display_type"] = -$_GET["display_type"];
        $_GET["export_all"]   = 1;
    }

    switch ($item::class) {
        case Stat::class:
            if (isset($_GET["item_type_param"])) {
                $params = Toolbox::decodeArrayFromInput($_GET["item_type_param"]);
                switch ($params["type"]) {
                    case "device":
                    case "comp_champ":
                        $val = Stat::getItems(
                            $_GET["itemtype"],
                            $params["date1"],
                            $params["date2"],
                            $params["dropdown"]
                        );
                        Stat::showTable(
                            $_GET["itemtype"],
                            $params["type"],
                            $params["date1"],
                            $params["date2"],
                            $params["start"],
                            $val,
                            $params["dropdown"]
                        );
                        break;

                    default:
                        $val2 = ($params['value2'] ?? 0);
                        $val  = Stat::getItems(
                            $_GET["itemtype"],
                            $params["date1"],
                            $params["date2"],
                            $params["type"],
                            $val2
                        );
                        Stat::showTable(
                            $_GET["itemtype"],
                            $params["type"],
                            $params["date1"],
                            $params["date2"],
                            $params["start"],
                            $val,
                            $val2
                        );
                }
            } elseif (isset($_GET["type"]) && ($_GET["type"] === "hardwares")) {
                Stat::showItems("", $_GET["date1"], $_GET["date2"], $_GET['start'], $_GET["itemtype"]);
            }
            break;

        default:
            // Plugin case
            if ($plug = isPluginItemType($item::class)) {
                if (Plugin::doOneHook($plug['plugin'], Hooks::AUTO_DYNAMIC_REPORT, $_GET)) {
                    return;
                }
            }
            $params = Search::manageParams($item::class, $_GET);
            Search::showList($item::class, $params);
    }
}
