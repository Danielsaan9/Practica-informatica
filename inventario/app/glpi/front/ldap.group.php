<?php

require_once(__DIR__ . '/_check_webserver_config.php');

use Glpi\Application\View\TemplateRenderer;

$group = new Group();
$group->checkGlobal(UPDATE);
Session::checkRight(User::$rightname, User::UPDATEAUTHENT);

Html::header(__('LDAP directory link'), '', "admin", "group", "ldap");

echo TemplateRenderer::getInstance()->render(
    'pages/admin/ldap.groups.html.twig'
);

Html::footer();
