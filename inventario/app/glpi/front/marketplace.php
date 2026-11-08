<?php

use Glpi\Marketplace\Controller;
use Glpi\Marketplace\View;

require_once(__DIR__ . '/_check_webserver_config.php');

(new Config())->checkGlobal(UPDATE);

if (!Controller::isWebAllowed()) {
    // Redirect to classic plugins page
    Html::redirect(Plugin::getSearchURL());
}
// This has to be called before search process is called, in order to add
// "new" plugins in DB to be able to display them.
$plugin = new Plugin();
$plugin->checkStates(true);

Html::header(__('Marketplace'), '', "config", "plugin", "marketplace");

$market_view = new View();
$market_view->display();

Html::footer();
