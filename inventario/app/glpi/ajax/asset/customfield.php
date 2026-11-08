<?php
use Glpi\Asset\CustomFieldDefinition;
use Glpi\Exception\Http\BadRequestHttpException;

Session::checkRight(CustomFieldDefinition::$rightname, READ);

if (isset($_POST['action'])) {
    $field = new CustomFieldDefinition();
    if ($_POST['action'] === 'get_default_custom_field') {
        $field->fields = $_POST;
        $field->fields['default_value'] = $field->getFieldType()->normalizeValue($_POST['default_value'] ?? null);
        echo $field->getFieldType()->getDefaultValueFormInput();
    } elseif ($_POST['action'] === 'get_field_type_options') {
        $field->getFromDB($_POST['customfielddefinitions_id']);
        $field->fields['type'] = $_POST['type'];
        $field_options = $field->getFieldType()->getOptions();
        foreach ($field_options as $option) {
            echo $option->getFormInput();
        }
    } elseif ($_POST['action'] === 'purge_field') {
        $field->check($_POST['customfielddefinitions_id'], PURGE);
        $field->delete(['id' => $_POST['customfielddefinitions_id']]);
    } else {
        throw new BadRequestHttpException();
    }
} else {
    throw new BadRequestHttpException();
}
