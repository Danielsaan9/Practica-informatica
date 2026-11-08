<?php

require_once(__DIR__ . '/_check_webserver_config.php');

use Glpi\Exception\AuthenticationFailedException;

use function Safe\session_destroy;

/**
 * @since 0.85
 */

global $CFG_GLPI;

if (!isset($_SESSION["glpicookietest"]) || ($_SESSION["glpicookietest"] != 'testcookie')) {
    if (!Session::canWriteSessionFiles()) {
        Html::redirect($CFG_GLPI['root_doc'] . "/index.php?error=2");
    } else {
        Html::redirect($CFG_GLPI['root_doc'] . "/index.php?error=1");
    }
}

if (isset($_POST['totp_code']) && is_array($_POST['totp_code'])) {
    $_POST['totp_code'] = implode('', $_POST['totp_code']);
}

$remember = ($_POST['login_remember'] ?? 0) && $CFG_GLPI["login_remember_time"];

$auth = new Auth();

// now we can continue with the process...
if (isset($_REQUEST['totp_cancel'])) {
    session_destroy();
    Html::redirect($CFG_GLPI['root_doc'] . '/index.php');
}
if ($auth->login($_POST['login_name'] ?? '', $_POST['login_password'] ?? '', ($_REQUEST["noAUTO"] ?? false), $remember, $_POST['auth'] ?? '')) {
    Auth::redirectIfAuthenticated();
} else {
    throw new AuthenticationFailedException(authentication_errors: $auth->getErrors());
}
