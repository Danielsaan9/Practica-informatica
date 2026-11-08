<?php

use Glpi\Toolbox\URL;

/**
 * @since 0.85
 */

global $CFG_GLPI;

if (
    $CFG_GLPI["ssovariables_id"] > 0
    && ((string) $CFG_GLPI['ssologout_url']) !== ''
) {
    Session::cleanOnLogout();
    Html::redirect(URL::sanitizeURL($CFG_GLPI["ssologout_url"]));
}

if (
    !isset($_SESSION["noAUTO"])
    && isset($_SESSION["glpiauthtype"])
    && $_SESSION["glpiauthtype"] == Auth::CAS
) {
    phpCAS::client(
        constant($CFG_GLPI["cas_version"]),
        $CFG_GLPI["cas_host"],
        intval($CFG_GLPI["cas_port"]),
        $CFG_GLPI["cas_uri"],
        $CFG_GLPI["url_base"],
        false
    );
    phpCAS::setServerLogoutURL(strval($CFG_GLPI["cas_logout"]));
    phpCAS::logout();
}

$toADD = "";

// Redirect management
if (isset($_POST['redirect']) && ((string) $_POST['redirect']) !== '') {
    $toADD = "?redirect=" . rawurlencode($_POST['redirect']);
} elseif (isset($_GET['redirect']) && ((string) $_GET['redirect']) !== '') {
    $toADD = "?redirect=" . rawurlencode($_GET['redirect']);
}

if (isset($_SESSION["noAUTO"]) || isset($_GET['noAUTO'])) {
    if (empty($toADD)) {
        $toADD .= "?";
    } else {
        $toADD .= "&";
    }
    $toADD .= "noAUTO=1";
}

Session::cleanOnLogout();

// Redirect to the login-page
Html::redirect($CFG_GLPI["root_doc"] . "/index.php" . $toADD);
