<?php

use Glpi\Application\View\TemplateRenderer;
use Glpi\Marketplace\View;

require_once(__DIR__ . '/_check_webserver_config.php');

(new Config())->checkGlobal(UPDATE);

// This has to be called before search process is called, in order to add
// "new" plugins in DB to be able to display them.
$plugin = new Plugin();
$plugin->checkStates(true);

Html::header(__('Setup'), '', "config", "plugin");

View::showFeatureSwitchDialog();

echo $plugin->getPluginsUpdatableAlert();
echo $plugin->getPluginsListSuspendBanner();

Search::show('Plugin');

echo TemplateRenderer::getInstance()->renderFromStringTemplate(<<<TWIG
    <div class="text-center my-2">
        <a href="https://plugins.glpi-project.org" class="btn btn-primary" role="button">
            <i class="ti ti-eye" aria-hidden="true"></i>
            <span>{{ label }}</span>
        </a>
    </div>
TWIG, ['label' => __('See the catalog of plugins')]);

Html::footer();
