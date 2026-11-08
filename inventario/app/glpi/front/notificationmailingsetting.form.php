<?php

require_once(__DIR__ . '/_check_webserver_config.php');

use Glpi\Error\ErrorHandler;
use Glpi\Event;
use Glpi\Http\RedirectResponse;
use Glpi\Mail\SMTP\OauthConfig;

Session::checkRight(Config::$rightname, UPDATE);

if (isset($_POST["update"])) {
    $config = new Config();
    $config->update($_POST);
    Event::log(0, "system", 3, "setup", sprintf(
        __('%1$s edited the emails notifications configuration'),
        $_SESSION["glpiname"] ?? __("Unknown"),
    ));

    $redirect_to_smtp_oauth = $_SESSION['redirect_to_smtp_oauth'] ?? false;
    unset($_SESSION['redirect_to_smtp_oauth']);
    if ($redirect_to_smtp_oauth) {
        $provider = OauthConfig::getInstance()->getSmtpOauthProvider();

        if ($provider !== null) {
            try {
                $auth_url = $provider->getAuthorizationUrl();
                $_SESSION['smtp_oauth2_state'] = $provider->getState();
                return new RedirectResponse($auth_url);
            } catch (Throwable $e) {
                ErrorHandler::logCaughtException($e);
                Session::addMessageAfterRedirect(
                    htmlescape(sprintf(_x('oauth', 'Authorization failed with error: %s'), $e->getMessage())),
                    false,
                    ERROR
                );
                Html::back();
            }
        }
    }

    Html::back();
}

$menus = ["config", "notification", NotificationMailingSetting::class];
$config_id = Config::getConfigIDForContext('core');
NotificationMailingSetting::displayFullPageForItem($config_id, $menus);
