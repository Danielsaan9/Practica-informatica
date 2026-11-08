<?php

use Glpi\Application\View\TemplateRenderer;

header("Content-Type: text/html; charset=UTF-8");
Html::header_nocache();

try {
    $ma = new MassiveAction($_POST, $_GET, 'specialize');
} catch (Throwable $e) {
    $twig_params = [
        'title' => __('Warning'),
        'text' => $e->getMessage(),
    ];
    // language=Twig
    echo TemplateRenderer::getInstance()->renderFromStringTemplate(<<<TWIG
        <div class="alert alert-warning">
            <i class="alert-icon ti ti-alert-triangle" aria-hidden="true"></i>
            <div class="alert-title">{{ title }}</div>
            <div class="text-secondary">{{ text }}</div>
        </div>
TWIG, $twig_params);
    return;
}

$ma->showSubForm();
