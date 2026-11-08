<?php

require_once(__DIR__ . '/_check_webserver_config.php');

use Glpi\Event;
use Glpi\Security\TOTPManager;

$user = new User();

// Manage 2FA
if (isset($_POST['disable_2fa'])) {
    $totp_manager = new TOTPManager();
    $totp_manager->disable2FAForUser(Session::getLoginUserID());
    Html::redirect(Preference::getSearchURL());
}

if (
    isset($_POST["update"])
    && ($_POST["id"] == Session::getLoginUserID())
) {
    $user->update($_POST);
    Event::log(
        $_POST["id"],
        "users",
        5,
        "setup",
        //TRANS: %s is the user login
        sprintf(__('%s updates an item'), $_SESSION["glpiname"])
    );
    Html::back();
} else {
    if (Session::getCurrentInterface() == "central") {
        Html::header(Preference::getTypeName(1), '', 'preference');
    } else {
        Html::helpHeader(Preference::getTypeName(1));
    }

    $pref = new Preference();
    $pref->display(['main_class' => 'tab_cadre_fixe']);

    if (Session::getCurrentInterface() == "central") {
        Html::footer();
    } else {
        Html::helpFooter();
    }
}
