<?php

switch ($_REQUEST['action']) {
    case "getActors":
        header("Content-Type: application/json; charset=UTF-8");
        Html::header_nocache();
        Session::writeClose();
        echo Dropdown::getDropdownActors($_POST);
        break;
}
