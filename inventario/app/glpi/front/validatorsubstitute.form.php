<?php

require_once(__DIR__ . '/_check_webserver_config.php');

if (isset($_POST['update'])) {
    $validator_substitute = new ValidatorSubstitute();
    $validator_substitute->check(-1, UPDATE, $_POST);
    if (!isset($_POST['substitutes']) && isset($_POST['_substitutes_defined'])) {
        // When the substitutes multiselect is empty
        $_POST['substitutes'] = [];
    }
    $validator_substitute->updateSubstitutes($_POST);
}
Html::back();
