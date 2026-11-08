<?php

require_once(__DIR__ . '/../_check_webserver_config.php');

use Glpi\ContentTemplates\TemplateManager;
use Glpi\Exception\Http\BadRequestHttpException;
use Glpi\Toolbox\MarkdownRenderer;

// Check mandatory parameter
$preset = $_GET['preset'] ?? null;
if (is_null($preset)) {
    throw new BadRequestHttpException("Missing mandatory 'preset' parameter");
}

Html::includeHeader(__("Template variables documentation"));
echo "<body class='documentation-page'>";
echo "<div id='page'>";
echo "<div class='documentation documentation-large'>";

// Parse markdown
$md = new MarkdownRenderer();
echo $md->render(TemplateManager::generateMarkdownDocumentation($preset));

echo "</div>";
echo "</div>";

// Footer closes main and div
echo "<main>";
echo "<div>";
Html::nullFooter();
