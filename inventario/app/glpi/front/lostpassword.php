<?php

require_once(__DIR__ . '/_check_webserver_config.php');

use Glpi\Application\View\TemplateRenderer;

global $CFG_GLPI;

if (
    !$CFG_GLPI['notifications_mailing']
    || !countElementsInTable(
        'glpi_notifications',
        ['itemtype' => User::class, 'event' => 'passwordforget', 'is_active' => 1]
    )
) {
    Session::addMessageAfterRedirect(
        __s('Sending password forget notification is not enabled.'),
        true,
        ERROR
    );
    TemplateRenderer::getInstance()->display('forgotpassword.html.twig', [
        'messages_only' => true,
    ]);
    return;
}

$user = new User();

if (isset($_POST['totp_code']) && is_array($_POST['totp_code'])) {
    $_REQUEST['totp_code'] = implode('', $_POST['totp_code']);
}

// Manage lost password
// REQUEST needed : GET on first access / POST on submit form
if (isset($_REQUEST['password_forget_token'])) {
    if (isset($_POST['password'])) {
        $user->showUpdateForgottenPassword($_REQUEST);
    } else {
        User::showPasswordForgetChangeForm($_REQUEST['password_forget_token']);
    }
} else {
    if (isset($_POST['email'])) {
        $user->showForgetPassword($_POST['email']);
    } else {
        User::showPasswordForgetRequestForm();
    }
}
