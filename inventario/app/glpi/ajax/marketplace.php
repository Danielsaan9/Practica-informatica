<?php

// follow download progress of a plugin with a minimal loading of files
// So we get a ajax answer in 5ms instead 100ms
if (($_GET["action"] ?? null) === "get_dl_progress") {
    echo $_SESSION['marketplace_dl_progress'][$_GET['key']] ?? 0;
    return;
}

(new Config())->checkGlobal(UPDATE);

use Glpi\Marketplace\Controller as MarketplaceController;
use Glpi\Marketplace\View as MarketplaceView;

if (isset($_POST['key']) && isset($_POST["action"])) {
    $marketplace_ctrl = new MarketplaceController($_POST['key']);
    if (
        $_POST["action"] == "download_plugin"
        || $_POST["action"] == "update_plugin"
    ) {
        $marketplace_ctrl->downloadPlugin();
    }
    if ($_POST["action"] == "clean_plugin") {
        if ($marketplace_ctrl->cleanPlugin()) {
            echo "cleaned";
        }
    }
    if ($_POST["action"] == "install_plugin") {
        $marketplace_ctrl->installPlugin();
    }
    if ($_POST["action"] == "uninstall_plugin") {
        $marketplace_ctrl->uninstallPlugin();
    }
    if ($_POST["action"] == "enable_plugin") {
        $marketplace_ctrl->enablePlugin();
    }
    if ($_POST["action"] == "disable_plugin") {
        $marketplace_ctrl->disablePlugin();
    }

    echo MarketplaceView::getButtons($_POST['key']);
} elseif (($_GET["action"] ?? null) == "refresh_plugin_list") {
    switch ($_GET['tab']) {
        default:
        case 'discover':
            MarketplaceView::discover(
                $_GET['force'] ?? false,
                true,
                $_GET['tag'] ?? "",
                $_GET['filter'] ?? "",
                (int) ($_GET['page'] ?? 1),
                $_GET['sort'] ?? "sort-alpha-asc"
            );
            break;
        case 'installed':
            MarketplaceView::installed(true, true, $_GET['filter'] ?? "");
            break;
    }
} elseif (($_GET["action"] ?? null) == "getPagination") {
    echo MarketplaceView::getPaginationHtml(
        (int) $_GET['page'],
        (int) $_GET['total'],
        true
    );
}
