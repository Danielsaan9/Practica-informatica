<?php

use Glpi\Application\View\TemplateRenderer;

use function Safe\json_encode;

header("Content-Type: text/html; charset=UTF-8");
Html::header_nocache();

if (!($_SESSION['telemetry_from_install'] ?? false)) {
    Session::checkRight(Config::$rightname, READ);
    $hide_sensitive_data = false;
} else {
    $hide_sensitive_data = true;
}

echo Html::css("lib/monaco.css");

$twig_params = [
    'info' => json_encode(Telemetry::getTelemetryInfos($hide_sensitive_data), JSON_PRETTY_PRINT),
    'description' => __("We only collect the following data: plugins usage, performance and responsiveness statistics about user interface features, memory, and hardware configuration."),
];
// language=Twig
echo TemplateRenderer::getInstance()->renderFromStringTemplate(<<<TWIG
    <p>{{ description }}</p>
    <div id='telemetry-preview' style="height: 400px"></div>
    <script type="module">
        import('/js/modules/Monaco/MonacoEditor.js').then(() => {
            window.GLPI.Monaco.createEditor('telemetry-preview', 'javascript', `{{ info|escape('js') }}`, [], {
                readOnly: true,
                minimap: {
                    enabled: false
                },
                automaticLayout: true
            });
        });
    </script>
TWIG, $twig_params);
