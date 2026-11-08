<?php

require_once(__DIR__ . '/_check_webserver_config.php');

Session::checkRight(User::$rightname, User::IMPORTEXTAUTHUSERS);

AuthLDAP::manageRequestValues();

if (isset($_REQUEST['_in_modal']) && $_REQUEST['_in_modal']) {
    $_REQUEST['_in_modal'] = 1;
}

Html::header(__('LDAP directory link'), '', "admin", "user", "ldap");

if (($_REQUEST['action'] ?? 'show') === 'show') {
    $authldap = new AuthLDAP();
    $authldap->getFromDB($_REQUEST['authldaps_id']);

    AuthLDAP::showUserImportForm($authldap);

    if (
        isset($_REQUEST['authldaps_id'])
        && (int) $_REQUEST['authldaps_id'] !== 0
        && (isset($_REQUEST['search']) || isset($_REQUEST['start']) || isset($_REQUEST['glpilist_limit']))
    ) {
        echo "<br />";
        AuthLDAP::searchUser($authldap);
    }
}

Html::footer();
