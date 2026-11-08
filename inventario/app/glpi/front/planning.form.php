<?php

require_once(__DIR__ . '/_check_webserver_config.php');

/**
 * @since 9.1
 */

if ($_REQUEST["action"] == "send_add_user_form") {
    Planning::sendAddUserForm($_REQUEST);
}

if ($_REQUEST["action"] == "send_add_group_users_form") {
    Planning::sendAddGroupUsersForm($_REQUEST);
}

if ($_REQUEST["action"] == "send_add_group_form") {
    Planning::sendAddGroupForm($_REQUEST);
}

if ($_REQUEST["action"] == "send_add_external_form") {
    Planning::sendAddExternalForm($_REQUEST);
}

Html::back();
