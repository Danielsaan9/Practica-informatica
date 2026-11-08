<?php

require_once __DIR__ . '/../../../inc/includes.php';

Session::checkCentralAccess();
Session::checkRight(Computer::$rightname, UPDATE);

if (!isset($_POST['computers_id']) || !ctype_digit((string) $_POST['computers_id'])) {
    Session::addMessageAfterRedirect('Computador no válido.', false, ERROR);
    Html::back();
}

$computer = new Computer();
$computer_id = (int) $_POST['computers_id'];
if (!$computer->getFromDB($computer_id) || !$computer->can($computer_id, UPDATE)) {
    Session::addMessageAfterRedirect('No tienes acceso a este computador.', false, ERROR);
    Html::back();
}

$model_id = (int) ($computer->fields['computermodels_id'] ?? 0);
$model_name = '';
$manufacturer_name = '';
if ($model_id > 0) {
    $model = new ComputerModel();
    if ($model->getFromDB($model_id)) {
        $model_name = (string) $model->fields['name'];
        $manufacturer_id = (int) ($model->fields['manufacturers_id'] ?? 0);
        if ($manufacturer_id > 0) {
            $manufacturer = new Manufacturer();
            if ($manufacturer->getFromDB($manufacturer_id)) {
                $manufacturer_name = (string) $manufacturer->fields['name'];
            }
        }
    }
}

if ($model_name === '') {
    Session::addMessageAfterRedirect('Este computador no tiene un modelo válido para consultar.', false, ERROR);
    Html::back();
}

global $DB;
$existing = null;
foreach ($DB->request([
    'FROM'  => 'glpi_plugin_fundacionluz_valuations',
    'WHERE' => ['itemtype' => Computer::class, 'models_id' => $model_id],
    'LIMIT' => 1,
]) as $row) {
    $existing = $row;
    break;
}
if ($existing !== null && str_starts_with((string) $existing['source'], 'Mercado Libre Chile')
    && (string) $existing['estimate_date'] === date('Y-m-d')) {
    Session::addMessageAfterRedirect('Ya existe una consulta de mercado de hoy para este modelo; se reutilizó para evitar llamadas repetidas.', false, INFO);
    Html::back();
}

$query = trim($manufacturer_name . ' ' . $model_name);
$result = PluginFundacionluzValuation::fetchMarketEstimate($query);
if (isset($result['error'])) {
    Session::addMessageAfterRedirect((string) $result['error'], false, ERROR);
    Html::back();
}

$estimate_fields = [
    'itemtype'          => Computer::class,
    'models_id'         => $model_id,
    'replacement_value' => (float) $result['value'],
    'source'            => 'Mercado Libre Chile · mediana de ' . (int) $result['count'] . ' publicaciones nuevas comparables',
    'estimate_date'     => (string) $result['date'],
    'users_id'          => Session::getLoginUserID(),
];
if ($existing !== null) {
    $DB->update('glpi_plugin_fundacionluz_valuations', $estimate_fields, ['id' => (int) $existing['id']]);
} else {
    $DB->insert('glpi_plugin_fundacionluz_valuations', $estimate_fields);
}

Session::addMessageAfterRedirect('Referencia de mercado actualizada para el modelo: ' . number_format((float) $result['value'], 0, ',', '.') . ' CLP, mediana de ' . (int) $result['count'] . ' publicaciones.', false, INFO);
Html::back();
