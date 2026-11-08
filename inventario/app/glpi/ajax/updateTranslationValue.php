<?php

use Glpi\Application\View\TemplateRenderer;

header("Content-Type: text/html; charset=UTF-8");
Html::header_nocache();

Session::checkRight(DropdownTranslation::$rightname, UPDATE);

$matching_field = null;

if (isset($_POST['itemtype'], $_POST['field']) && is_a($_POST['itemtype'], CommonDropdown::class, true)) {
    $itemtype = getItemForItemtype($_POST['itemtype']);
    $matching_field = $itemtype->getAdditionalField($_POST['field']);
}

$field_type = $matching_field['type'] ?? null;

// language=twig
echo TemplateRenderer::getInstance()->renderFromStringTemplate(<<<TWIG
    {% import 'components/form/basic_inputs_macros.html.twig' as inputs %}
    {% if field_type == 'tinymce' %}
        {{ inputs.textarea('value', '', {
            enable_richtext: true,
            enable_images: false,
            enable_fileupload: false,
        }) }}
    {% else %}
        {{ inputs.text('value', '') }}
    {% endif %}
TWIG, ['field_type' => $field_type]);
