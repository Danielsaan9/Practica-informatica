<?php

header("Content-Type: text/html; charset=UTF-8");
Html::header_nocache();

Session::checkRight(Fieldblacklist::$rightname, UPDATE);
if (
    isset($_POST['itemtype'])
    && isset($_POST['id_field'])
) {
    $blacklist = new Fieldblacklist();
    if (isset($_POST['id']) && ($_POST['id'] > 0)) {
        $blacklist->getFromDB($_POST['id']);
    } else {
        $blacklist->getEmpty();
    }
    $blacklist->fields['field']    = $_POST['id_field'];
    $blacklist->fields['itemtype'] = $_POST['itemtype'];
    $blacklist->selectValues($_POST['id_field']);
}
