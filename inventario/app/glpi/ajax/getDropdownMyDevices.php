<?php

/**
 * List of user devices for dropdown with lazy loading.
 */

header("Content-Type: application/json; charset=UTF-8");
Html::header_nocache();

echo Dropdown::getDropdownMyDevices($_POST);
