<?php

require_once(__DIR__ . '/_check_webserver_config.php');

Session::checkRightsOr(Group::$rightname, [CREATE, UPDATE]);
Session::checkRight(User::$rightname, User::UPDATEAUTHENT);
AuthLDAP::manageRequestValues(false);

Html::header(__('LDAP directory link'), '', "admin", "group", "ldap");

$authldap = new AuthLDAP();
$authldap->getFromDB($_REQUEST['authldaps_id'] ?? 0);
AuthLDAP::showGroupImportForm($authldap);

if (
    (isset($_REQUEST['authldaps_id']) && ((int) $_REQUEST['authldaps_id'] > 0))
    && (isset($_REQUEST['search']) || isset($_REQUEST['start']) || isset($_REQUEST['glpilist_limit']))
) {
    AuthLDAP::showLdapGroups(
        $_REQUEST['start'] ?? 0,
        0,
        $_REQUEST["ldap_group_filter"] ?? '',
        $_REQUEST["ldap_group_filter2"] ?? '',
        $_SESSION["glpiactive_entity"]
    );
}

Html::footer();
