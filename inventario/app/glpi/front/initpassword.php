<?php

require_once(__DIR__ . '/_check_webserver_config.php');

use Glpi\Application\View\TemplateRenderer;

global $CFG_GLPI;

if (
    !$CFG_GLPI['notifications_mailing']
    || !countElementsInTable(
        'glpi_notifications',
        ['itemtype' => User::class, 'event' => 'passwordinit', 'is_active' => 1]
    )
) {
    Session::addMessageAfterRedirect(
        __s('Sending password initialization notification is not enabled.'),
        true,
        ERROR
    );
    TemplateRenderer::getInstance()->display('password_form.html.twig', [
        'title'         => __('Forgotten initialization'),
        'messages_only' => true,
    ]);
    return;
}

$user = new User();

// Manage password initialization
// REQUEST needed : GET on first access / POST on submit form
if (isset($_REQUEST['password_forget_token'])) {
    if (isset($_POST['password'])) {
        $user->showUpdateForgottenPassword($_REQUEST);
    } else {
        User::showPasswordInitChangeForm($_REQUEST['password_forget_token']);
    }
} else {
    if (isset($_POST['email'])) {
        $user->showInitPassword($_POST['email']);
    } else {
        User::showPasswordInitRequestForm();
    }
}
