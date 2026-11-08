<?php

header("Content-Type: text/html; charset=UTF-8");
Html::header_nocache();

Session::checkRight(Fieldblacklist::$rightname, UPDATE);

$field = new Fieldblacklist();
if ($_POST['id'] > 0) {
    $field->getFromDB($_POST['id']);
} else {
    $field->getEmpty();
    $field->fields['itemtype'] = $_POST['itemtype'];
}
$field->selectCriterias();
