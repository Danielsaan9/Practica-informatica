<?php

require_once __DIR__ . '/../../../inc/includes.php';

Session::checkCentralAccess();
Session::checkRight(Computer::$rightname, READ);

global $DB;

$asset_types = [
    'Computer' => ['label' => 'Computadores', 'table' => 'glpi_computers', 'models' => 'glpi_computermodels', 'fk' => 'computermodels_id', 'right' => Computer::$rightname],
    'Monitor' => ['label' => 'Monitores', 'table' => 'glpi_monitors', 'models' => 'glpi_monitormodels', 'fk' => 'monitormodels_id', 'right' => Monitor::$rightname],
    'NetworkEquipment' => ['label' => 'Dispositivos de red', 'table' => 'glpi_networkequipments', 'models' => 'glpi_networkequipmentmodels', 'fk' => 'networkequipmentmodels_id', 'right' => NetworkEquipment::$rightname],
    'Peripheral' => ['label' => 'Periféricos', 'table' => 'glpi_peripherals', 'models' => 'glpi_peripheralmodels', 'fk' => 'peripheralmodels_id', 'right' => Peripheral::$rightname],
    'Printer' => ['label' => 'Impresoras', 'table' => 'glpi_printers', 'models' => 'glpi_printermodels', 'fk' => 'printermodels_id', 'right' => Printer::$rightname],
    'Phone' => ['label' => 'Teléfonos', 'table' => 'glpi_phones', 'models' => 'glpi_phonemodels', 'fk' => 'phonemodels_id', 'right' => Phone::$rightname],
];

$format_clp = static fn(float $value): string => '$' . number_format($value, 0, ',', '.') . ' CLP';
$totals = ['purchase' => 0.0, 'replacement' => 0.0, 'missing' => 0, 'purchase_incomplete' => 0, 'stale' => 0, 'quantity' => 0];
$rows = [];
$model_options = [];
$editable_types = array_filter(
    $asset_types,
    static fn(array $spec): bool => Session::haveRight($spec['right'], UPDATE)
);

if (isset($_POST['save_estimate'])) {
    $model_key = explode(':', (string) ($_POST['model_key'] ?? ''), 2);
    $type = $model_key[0] ?? '';
    $model_id = filter_var($model_key[1] ?? null, FILTER_VALIDATE_INT);
    $value = filter_var($_POST['replacement_value'] ?? null, FILTER_VALIDATE_FLOAT);
    $source = trim((string) ($_POST['source'] ?? ''));
    $date = (string) ($_POST['estimate_date'] ?? '');
    $parsed_date = DateTimeImmutable::createFromFormat('!Y-m-d', $date);
    $date_valid = $parsed_date !== false && $parsed_date->format('Y-m-d') === $date;

    $visible_model = false;
    if (isset($editable_types[$type]) && $model_id > 0 && Session::haveRight($asset_types[$type]['right'], READ)) {
        $model_where = ['is_deleted' => 0, $asset_types[$type]['fk'] => $model_id] + getEntitiesRestrictCriteria($asset_types[$type]['table']);
        foreach ($DB->request([
            'SELECT' => ['id'],
            'FROM'   => $asset_types[$type]['table'],
            'WHERE'  => $model_where,
            'LIMIT'  => 1,
        ]) as $visible_asset) {
            $visible_model = true;
            break;
        }
    }

    if ($visible_model && $value !== false && $value > 0
        && $source !== '' && mb_strlen($source) <= 255 && $date_valid) {
        $estimate_fields = [
            'itemtype'          => $type,
            'models_id'         => $model_id,
            'replacement_value' => $value,
            'source'            => $source,
            'estimate_date'     => $date,
            'users_id'          => Session::getLoginUserID(),
        ];
        $existing_id = 0;
        foreach ($DB->request([
            'SELECT' => ['id'],
            'FROM'   => 'glpi_plugin_fundacionluz_valuations',
            'WHERE'  => ['itemtype' => $type, 'models_id' => $model_id],
        ]) as $existing) {
            $existing_id = (int) $existing['id'];
            break;
        }
        if ($existing_id > 0) {
            $DB->update('glpi_plugin_fundacionluz_valuations', $estimate_fields, ['id' => $existing_id]);
        } else {
            $DB->insert('glpi_plugin_fundacionluz_valuations', $estimate_fields);
        }
        Session::addMessageAfterRedirect('Estimación guardada.', false, INFO);
    } else {
        Session::addMessageAfterRedirect('Revisa el tipo, modelo, valor, fuente y fecha de la estimación.', false, ERROR);
    }
    Html::back();
}

foreach ($asset_types as $type => $spec) {
    if (!Session::haveRight($spec['right'], READ)) {
        continue;
    }

    $asset_where = ['is_deleted' => 0] + getEntitiesRestrictCriteria($spec['table']);
    if ($type === 'Computer') {
        $asset_where['is_template'] = 0;
    }

    $assets = [];
    foreach ($DB->request([
        'SELECT' => ['id', $spec['fk']],
        'FROM'   => $spec['table'],
        'WHERE'  => $asset_where,
    ]) as $asset) {
        $assets[(int) $asset['id']] = (int) $asset[$spec['fk']];
    }

    $purchase = 0.0;
    $purchase_by_asset = [];
    if ($assets !== []) {
        foreach ($DB->request([
            'SELECT' => ['items_id', 'value', 'buy_date'],
            'FROM'   => 'glpi_infocoms',
            'WHERE'  => ['itemtype' => $type, 'items_id' => array_keys($assets)],
        ]) as $infocom) {
            $amount = (float) $infocom['value'];
            $buy_date = (string) ($infocom['buy_date'] ?? '');
            if ($amount > 0) {
                $purchase += $amount;
            }
            $purchase_by_asset[(int) $infocom['items_id']] = $amount > 0
                && $buy_date !== ''
                && $buy_date !== '0000-00-00';
        }
    }
    foreach (array_keys($assets) as $asset_id) {
        if (!($purchase_by_asset[$asset_id] ?? false)) {
            $totals['purchase_incomplete']++;
        }
    }

    $estimates = [];
    foreach ($DB->request([
        'SELECT' => ['models_id', 'replacement_value', 'source', 'estimate_date'],
        'FROM'   => 'glpi_plugin_fundacionluz_valuations',
        'WHERE'  => ['itemtype' => $type],
    ]) as $estimate) {
        $estimates[(int) $estimate['models_id']] = $estimate;
    }

    $model_counts = [];
    $assets_without_model = 0;
    foreach ($assets as $model_id) {
        if ($model_id > 0) {
            $model_counts[$model_id] = ($model_counts[$model_id] ?? 0) + 1;
        } else {
            $assets_without_model++;
        }
    }

    $type_replacement = 0.0;
    $type_missing = 0;
    $models_by_id = [];
    if ($model_counts !== []) {
        foreach ($DB->request([
            'SELECT' => ['id', 'name'],
            'FROM'   => $spec['models'],
            'WHERE'  => ['id' => array_keys($model_counts)],
        ]) as $model_row) {
            $models_by_id[(int) $model_row['id']] = $model_row;
        }
    }

    foreach ($model_counts as $model_id => $quantity) {
        $model_row = $models_by_id[$model_id] ?? null;

        if ($model_row === null) {
            continue;
        }

        $estimate = $estimates[$model_id] ?? null;
        $line_value = $estimate ? (float) $estimate['replacement_value'] * $quantity : 0.0;
        if ($estimate === null) {
            $type_missing += $quantity;
        } else {
            $type_replacement += $line_value;
            $estimate_date = new DateTimeImmutable((string) $estimate['estimate_date']);
            $today = new DateTimeImmutable('today');
            $estimate_age = $estimate_date > $today
                ? PluginFundacionluzValuation::getMarketEstimateMaxAgeDays() + 1
                : $estimate_date->diff($today)->days;
            if ($estimate_age > PluginFundacionluzValuation::getMarketEstimateMaxAgeDays()) {
                $totals['stale'] += $quantity;
            }
        }

        $rows[] = [
            'type' => $spec['label'],
            'model' => (string) $model_row['name'],
            'quantity' => $quantity,
            'purchase' => null,
            'replacement' => $estimate ? (float) $estimate['replacement_value'] : null,
            'line_value' => $line_value,
            'source' => $estimate['source'] ?? '',
            'date' => $estimate['estimate_date'] ?? '',
            'stale' => $estimate !== null && $estimate_age > PluginFundacionluzValuation::getMarketEstimateMaxAgeDays(),
        ];

        if (isset($editable_types[$type])) {
            $model_options[] = [
                'type' => $type,
                'label' => $spec['label'] . ' · ' . $model_row['name'],
                'id' => $model_id,
            ];
        }
    }

    $rows[] = [
        'type' => $spec['label'],
        'model' => 'Total de categoría',
        'quantity' => count($assets),
        'purchase' => $purchase,
        'replacement' => null,
        'line_value' => $type_replacement,
        'source' => '',
        'date' => '',
        'summary' => true,
    ];

    $totals['purchase'] += $purchase;
    $totals['replacement'] += $type_replacement;
    $totals['missing'] += $type_missing + $assets_without_model;
    $totals['quantity'] += count($assets);
}

Html::header('Panel económico de activos', $_SERVER['PHP_SELF'], 'assets');
?>
<main class="container-fluid foundation-economy">
  <header class="foundation-economy__header">
    <div>
      <h1>Valor económico del inventario</h1>
      <p>Costos registrados y reposición estimada, en pesos chilenos.</p>
    </div>
    <time datetime="<?= htmlescape(date('Y-m-d')) ?>">Actualizado <?= htmlescape(date('d/m/Y')) ?></time>
  </header>

  <section class="foundation-economy__totals" aria-label="Resumen económico">
    <article><span>Costo de compra registrado</span><strong><?= htmlescape($format_clp($totals['purchase'])) ?></strong><small>Según valores de compra guardados en GLPI</small></article>
    <article><span>Reposición estimada</span><strong><?= htmlescape($format_clp($totals['replacement'])) ?></strong><small>Solo modelos con valor, fuente y fecha ingresados</small></article>
    <article><span>Activos sin estimación</span><strong><?= htmlescape((string) $totals['missing']) ?></strong><small>de <?= htmlescape((string) $totals['quantity']) ?> activos en categorías incluidas</small></article>
    <article><span>Activos sin costo o fecha de compra</span><strong><?= htmlescape((string) $totals['purchase_incomplete']) ?></strong><small>La depreciación requiere ambos datos</small></article>
    <article><span>Referencias de mercado desactualizadas</span><strong><?= htmlescape((string) $totals['stale']) ?></strong><small>Precios con más de <?= htmlescape((string) PluginFundacionluzValuation::getMarketEstimateMaxAgeDays()) ?> días</small></article>
  </section>

  <section class="foundation-economy__section">
    <h2>Detalle por modelo</h2>
    <div class="table-responsive">
      <table class="table table-vcenter table-hover">
        <thead><tr><th>Activo</th><th>Modelo</th><th class="text-end">Unidades</th><th class="text-end">Compra registrada</th><th class="text-end">Reposición unitaria</th><th class="text-end">Reposición total</th><th>Fuente y fecha</th></tr></thead>
        <tbody>
        <?php foreach ($rows as $row): ?>
          <tr<?= !empty($row['summary']) ? ' class="table-light fw-bold"' : '' ?>>
            <td><?= htmlescape($row['type']) ?></td><td><?= htmlescape($row['model']) ?></td>
            <td class="text-end"><?= htmlescape((string) $row['quantity']) ?></td>
            <td class="text-end"><?= $row['purchase'] === null ? '—' : htmlescape($format_clp($row['purchase'])) ?></td>
            <td class="text-end"><?= $row['replacement'] === null ? '—' : htmlescape($format_clp($row['replacement'])) ?></td>
            <td class="text-end"><?= htmlescape($format_clp($row['line_value'])) ?></td>
            <td><?= $row['source'] === '' ? 'Pendiente de estimar' : htmlescape($row['source'] . ' · ' . Html::convDate($row['date'])) ?><?= !empty($row['stale']) ? ' · Referencia desactualizada' : '' ?></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </section>

  <?php if ($editable_types !== [] && $model_options !== []): ?>
  <section class="foundation-economy__section">
    <h2>Registrar o actualizar reposición</h2>
    <p>Usa un precio unitario de referencia e indica la fuente consultada. La fecha permite reconocer estimaciones desactualizadas.</p>
    <form method="post" class="foundation-economy__form">
      <label>Modelo
        <select class="form-select" name="model_key" required>
          <?php foreach ($model_options as $option): ?>
            <option value="<?= htmlescape($option['type'] . ':' . $option['id']) ?>"><?= htmlescape($option['label']) ?></option>
          <?php endforeach; ?>
        </select>
      </label>
      <label>Valor unitario (CLP)<input class="form-control" type="number" name="replacement_value" min="1" step="1" required></label>
      <label>Fuente<input class="form-control" type="text" name="source" maxlength="255" placeholder="Tienda, cotización o URL" required></label>
      <label>Fecha de referencia<input class="form-control" type="date" name="estimate_date" value="<?= htmlescape(date('Y-m-d')) ?>" required></label>
      <button class="btn btn-primary" type="submit" name="save_estimate" value="1">Guardar estimación</button>
    </form>
  </section>
  <?php endif; ?>
</main>
<?php
Html::footer();
