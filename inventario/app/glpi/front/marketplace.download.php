<?php

require_once(__DIR__ . '/_check_webserver_config.php');

use Glpi\Exception\Http\AccessDeniedHttpException;
use Glpi\Marketplace\Controller as MarketplaceController;

Session::checkRight(Config::$rightname, UPDATE);

if (!MarketplaceController::isWebAllowed()) {
    throw new AccessDeniedHttpException();
}
if (isset($_REQUEST['key'])) {
    $marketplace_ctrl = new MarketplaceController($_REQUEST['key']);
    return $marketplace_ctrl->proxifyPluginArchive();
}
