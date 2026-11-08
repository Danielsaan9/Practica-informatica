<?php

require_once(__DIR__ . '/_check_webserver_config.php');

use Glpi\Application\View\TemplateRenderer;

Session::checkSeveralRightsOr(['rule_dictionnary_dropdown' => READ,
    'rule_dictionnary_software' => READ,
]);

Html::header(_n('Dictionary', 'Dictionaries', Session::getPluralNumber()), '', "admin", "dictionnary");

echo TemplateRenderer::getInstance()->render('pages/admin/rules/backup_header.html.twig');
echo TemplateRenderer::getInstance()->render(
    'pages/admin/rules/collections_list.html.twig',
    [
        'rules_group' => RuleCollection::getDictionnaries(),
    ]
);
Html::footer();
