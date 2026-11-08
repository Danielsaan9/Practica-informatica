<?php

/**
 * @since 0.85
 */

header("Content-Type: application/json; charset=UTF-8");
Html::header_nocache();

/**
 * Safe JSON response.
 * @psalm-taint-escape has_quotes
 * @psalm-taint-escape html
 */
$response = Dropdown::getDropdownNumber($_POST, json: true);

echo $response;
