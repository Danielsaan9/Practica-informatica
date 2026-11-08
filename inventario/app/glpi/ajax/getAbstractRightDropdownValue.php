<?php

use function Safe\json_encode;

header("Content-Type: application/json; charset=UTF-8");
Html::header_nocache();
function show_rights_dropdown(string $class, array $options = []): void
{
    $search = $_POST['searchText'] ?? "";
    echo json_encode($class::fetchValues($search, $options));
}
