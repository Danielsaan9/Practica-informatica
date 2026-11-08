<?php
use Glpi\Asset\AssetDefinition;
use Glpi\Exception\Http\BadRequestHttpException;
use Glpi\Exception\Http\NotFoundHttpException;

Session::checkRight(AssetDefinition::$rightname, READ);

if ($_REQUEST['action'] === 'get_all_fields') {
    Session::writeClose();
    header("Content-Type: application/json; charset=UTF-8");
    $definition = new AssetDefinition();
    if (!$definition->getFromDB($_GET['assetdefinitions_id'])) {
        throw new NotFoundHttpException();
    }
    $all_fields = $definition->getAllFields();
    $field_results = [];
    foreach ($all_fields as $k => $v) {
        if (!empty($_POST['searchText']) && stripos($v['text'], (string) $_POST['searchText']) === false) {
            continue;
        }
        $v['id'] = $k;
        $field_results[] = $v;
    }

    /**
     * Safe JSON response.
     * @psalm-taint-escape has_quotes
     * @psalm-taint-escape html
     */
    $response = json_encode([
        'results' => $field_results,
        'count' => count($all_fields),
    ], JSON_THROW_ON_ERROR);

    echo $response;
    return;
} elseif ($_REQUEST['action'] === 'get_core_field_editor') {
    header("Content-Type: text/html; charset=UTF-8");
    $asset_definition = new AssetDefinition();
    if (!$asset_definition->getFromDB($_GET['assetdefinitions_id'])) {
        throw new NotFoundHttpException();
    }
    $asset_definition->showFieldOptionsForCoreField($_GET['key'], $_GET['field_options'] ?? []);
    return;
}
throw new BadRequestHttpException();
