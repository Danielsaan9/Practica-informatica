<?php

header("Content-Type: application/json; charset=UTF-8");
Html::header_nocache();

/**
 * Safe JSON response.
 * @psalm-taint-escape has_quotes
 * @psalm-taint-escape html
 */
$response = json_encode(Html::getMenuFuzzySearchList(), JSON_THROW_ON_ERROR);

echo $response;
