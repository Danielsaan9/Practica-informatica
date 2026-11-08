<?php

require_once(__DIR__ . '/_check_webserver_config.php');

use Glpi\Application\View\TemplateRenderer;

Session::checkRight(User::$rightname, User::IMPORTEXTAUTHUSERS);

Html::header(__('LDAP directory link'), '', "admin", "user", "ldap");

echo TemplateRenderer::getInstance()->render(
    'pages/admin/ldap.users.html.twig'
);

Html::footer();
