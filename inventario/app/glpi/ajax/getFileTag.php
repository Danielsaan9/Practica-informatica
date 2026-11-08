<?php

/**
 * @since 0.85
 */

use function Safe\json_encode;

header('Content-type: application/json; charset=UTF-8');
Html::header_nocache();

if (isset($_POST['data'])) {
    $response = [];

    foreach (array_keys($_POST['data']) as $key) {
        $unique_name = Rule::getUuid();
        $response[$key] = ['tag' => Document::getImageTag($unique_name), 'name' => $unique_name];
    }

    echo json_encode($response);
}
