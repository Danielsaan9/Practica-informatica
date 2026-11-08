<?php

header("Content-Type: text/html; charset=UTF-8");
Html::header_nocache();

Session::checkRight(FieldUnicity::$rightname, UPDATE);

$field = new FieldUnicity();
if ($_POST['id'] > 0) {
    $field->getFromDB($_POST['id']);
} else {
    $field->getEmpty();
    $field->fields['itemtype'] = $_POST['itemtype'];
}
FieldUnicity::selectCriterias($field);
