<?php

require_once(__DIR__ . '/_check_webserver_config.php');

global $CFG_GLPI;

$config_ldap = new AuthLDAP();
$config_ldap->checkGlobal(UPDATE);

if (!isset($_GET['id'])) {
    $_GET['id'] = "";
}
//LDAP Server add/update/delete
if (isset($_POST["update"])) {
    $config_ldap->update($_POST);
    Html::back();
} elseif (isset($_POST["add"])) {
    if ($newID = $config_ldap->add($_POST)) {
        if (isset($_POST["host"]) && trim($_POST["host"]) != "") {
            if (AuthLDAP::testLDAPConnection($newID)) {
                Session::addMessageAfterRedirect(__s('Test successful'));
            } else {
                Session::addMessageAfterRedirect(__s('Test failed'), false, ERROR);
                GLPINetwork::addErrorMessageAfterRedirect();
            }
        }
        Html::redirect($CFG_GLPI["root_doc"] . "/front/authldap.php?next=extauth_ldap&id=" . $newID);
    }
    Html::back();
} elseif (isset($_POST["purge"])) {
    $config_ldap->delete($_POST, true);
    $_SESSION['glpi_authconfig'] = 1;
    $config_ldap->redirectToList();
} elseif (isset($_POST["add_replicate"])) {
    $replicate = new AuthLdapReplicate();
    unset($_POST["next"]);
    unset($_POST["id"]);
    $replicate->add($_POST);
    Html::back();
}

$menus = ['config', 'auth', 'AuthLDAP'];
AuthLDAP::displayFullPageForItem($_GET['id'], $menus, $_GET);
