<?php

use Glpi\DBAL\QueryExpression;

require_once __DIR__ . '/../../../inc/includes.php';

Session::checkCentralAccess();
Session::checkRight(Computer::$rightname, READ);

global $DB;

$asset_types = [
    'Computer' => ['label' => 'Computadores', 'table' => 'glpi_computers', 'model' => 'computermodels_id', 'right' => Computer::$rightname, 'url' => '/front/computer.php'],
    'Monitor' => ['label' => 'Monitores', 'table' => 'glpi_monitors', 'model' => 'monitormodels_id', 'right' => Monitor::$rightname, 'url' => '/front/monitor.php'],
    'NetworkEquipment' => ['label' => 'Dispositivos de red', 'table' => 'glpi_networkequipments', 'model' => 'networkequipmentmodels_id', 'right' => NetworkEquipment::$rightname, 'url' => '/front/networkequipment.php'],
    'Peripheral' => ['label' => 'Periféricos', 'table' => 'glpi_peripherals', 'model' => 'peripheralmodels_id', 'right' => Peripheral::$rightname, 'url' => '/front/peripheral.php'],
    'Printer' => ['label' => 'Impresoras', 'table' => 'glpi_printers', 'model' => 'printermodels_id', 'right' => Printer::$rightname, 'url' => '/front/printer.php'],
    'Phone' => ['label' => 'Teléfonos', 'table' => 'glpi_phones', 'model' => 'phonemodels_id', 'right' => Phone::$rightname, 'url' => '/front/phone.php'],
];

$cards = [];
$total_assets = 0;
$total_missing_models = 0;
$total_missing_identifiers = 0;
$computers_missing_location = 0;
$computers_missing_user = 0;

foreach ($asset_types as $type => $spec) {
    if (!Session::haveRight($spec['right'], READ)) {
        continue;
    }

    $where = ['is_deleted' => 0] + getEntitiesRestrictCriteria($spec['table']);
    if ($type === 'Computer') {
        $where['is_template'] = 0;
    }

    $stats = [
        'total' => 0,
        'missing_model' => 0,
        'missing_identifier' => 0,
        'missing_location' => 0,
        'missing_user' => 0,
    ];
    $select = [
        new QueryExpression('COUNT(*) AS total'),
        new QueryExpression('SUM(CASE WHEN COALESCE(`' . $spec['model'] . '`, 0) = 0 THEN 1 ELSE 0 END) AS missing_model'),
        new QueryExpression("SUM(CASE WHEN COALESCE(`serial`, '') = '' AND COALESCE(`otherserial`, '') = '' THEN 1 ELSE 0 END) AS missing_identifier"),
    ];
    if ($type === 'Computer') {
        $select[] = new QueryExpression('SUM(CASE WHEN COALESCE(`locations_id`, 0) = 0 THEN 1 ELSE 0 END) AS missing_location');
        $select[] = new QueryExpression('SUM(CASE WHEN COALESCE(`users_id`, 0) = 0 THEN 1 ELSE 0 END) AS missing_user');
    }
    $iterator = $DB->request([
        'SELECT' => $select,
        'FROM' => $spec['table'],
        'WHERE' => $where,
    ]);
    foreach ($iterator as $row) {
        $stats = [
            'total' => (int) $row['total'],
            'missing_model' => (int) ($row['missing_model'] ?? 0),
            'missing_identifier' => (int) ($row['missing_identifier'] ?? 0),
            'missing_location' => (int) ($row['missing_location'] ?? 0),
            'missing_user' => (int) ($row['missing_user'] ?? 0),
        ];
        break;
    }

    $cards[] = $spec + $stats;
    $total_assets += $stats['total'];
    $total_missing_models += $stats['missing_model'];
    $total_missing_identifiers += $stats['missing_identifier'];
    $computers_missing_location += $stats['missing_location'];
    $computers_missing_user += $stats['missing_user'];
}

$computer_spec = $asset_types['Computer'];
$computer_where = ['is_deleted' => 0, 'is_template' => 0] + getEntitiesRestrictCriteria($computer_spec['table']);
$serial_seen = [];
$inventory_seen = [];
$duplicate_serials = 0;
$duplicate_inventory_numbers = 0;
$incomplete_computers = [];

foreach ($DB->request([
    'SELECT' => ['id', 'name', 'serial', 'otherserial', 'computermodels_id', 'locations_id', 'users_id'],
    'FROM' => $computer_spec['table'],
    'WHERE' => $computer_where,
    'LIMIT' => 5000,
]) as $computer) {
    $serial = mb_strtolower(trim((string) $computer['serial']));
    $inventory_number = mb_strtolower(trim((string) $computer['otherserial']));
    if ($serial !== '') {
        $serial_seen[$serial] = ($serial_seen[$serial] ?? 0) + 1;
    }
    if ($inventory_number !== '') {
        $inventory_seen[$inventory_number] = ($inventory_seen[$inventory_number] ?? 0) + 1;
    }

    $missing = [];
    if ((int) $computer['computermodels_id'] === 0) {
        $missing[] = 'modelo';
    }
    if ($serial === '') {
        $missing[] = 'serie';
    }
    if ($inventory_number === '') {
        $missing[] = 'número de inventario';
    }
    if ((int) $computer['locations_id'] === 0) {
        $missing[] = 'ubicación';
    }
    if ((int) $computer['users_id'] === 0) {
        $missing[] = 'persona asignada';
    }
    if ($missing !== [] && count($incomplete_computers) < 10) {
        $incomplete_computers[] = [
            'id' => (int) $computer['id'],
            'name' => trim((string) $computer['name']) !== '' ? (string) $computer['name'] : 'Sin nombre',
            'missing' => $missing,
        ];
    }
}

$duplicate_serials = count(array_filter($serial_seen, static fn(int $count): bool => $count > 1));
$duplicate_inventory_numbers = count(array_filter($inventory_seen, static fn(int $count): bool => $count > 1));
$valuation_url = '/plugins/fundacionluz/front/dashboard.php';

Html::header('Resumen de inventario Fundación Luz', $_SERVER['PHP_SELF'], 'assets');
?>
<main class="container-fluid foundation-overview">
  <header class="foundation-overview__header">
    <div>
      <p class="foundation-overview__eyebrow">Fundación Luz · Activos</p>
      <h1>Resumen del inventario</h1>
      <p>Conteos según los activos visibles para tu perfil, excluyendo elementos eliminados y plantillas.</p>
    </div>
    <div class="foundation-overview__actions">
      <time datetime="<?= htmlescape(date('Y-m-d')) ?>">Actualizado <?= htmlescape(date('d/m/Y H:i')) ?></time>
      <a class="btn btn-primary" href="<?= htmlescape($valuation_url) ?>">Ver panel económico</a>
    </div>
  </header>

  <section class="foundation-overview__section" aria-label="Activos registrados">
    <div class="foundation-overview__section-heading">
      <div><h2>Activos registrados</h2><p><?= htmlescape((string) $total_assets) ?> activos en las categorías disponibles para este perfil</p></div>
    </div>
    <div class="foundation-overview__cards">
      <?php foreach ($cards as $card): ?>
      <a class="foundation-overview__card" href="<?= htmlescape($card['url']) ?>">
        <span><?= htmlescape($card['label']) ?></span>
        <strong><?= htmlescape(number_format($card['total'], 0, ',', '.')) ?></strong>
        <small>Sin modelo: <?= htmlescape(number_format($card['missing_model'], 0, ',', '.')) ?> · Sin identificador: <?= htmlescape(number_format($card['missing_identifier'], 0, ',', '.')) ?></small>
      </a>
      <?php endforeach; ?>
    </div>
  </section>

  <section class="foundation-overview__section" aria-label="Calidad de datos">
    <div class="foundation-overview__section-heading">
      <div><h2>Calidad de la información</h2><p>Alertas para revisión; no se modifica ni elimina ningún registro desde este panel.</p></div>
    </div>
    <div class="foundation-overview__quality">
      <article><span>Activos sin modelo</span><strong><?= htmlescape(number_format($total_missing_models, 0, ',', '.')) ?></strong><small>En las categorías visibles para tu perfil</small></article>
      <article><span>Activos sin serie ni número de inventario</span><strong><?= htmlescape(number_format($total_missing_identifiers, 0, ',', '.')) ?></strong><small>Uno de esos identificadores es necesario para una búsqueda precisa</small></article>
      <article><span>Computadores con series repetidas</span><strong><?= htmlescape(number_format($duplicate_serials, 0, ',', '.')) ?></strong><small>Grupos detectados entre los registros visibles</small></article>
      <article><span>Computadores con inventario repetido</span><strong><?= htmlescape(number_format($duplicate_inventory_numbers, 0, ',', '.')) ?></strong><small>Grupos detectados entre los registros visibles</small></article>
      <article><span>Computadores sin ubicación</span><strong><?= htmlescape(number_format($computers_missing_location, 0, ',', '.')) ?></strong><small>Requieren revisión de la ficha</small></article>
      <article><span>Computadores sin persona asignada</span><strong><?= htmlescape(number_format($computers_missing_user, 0, ',', '.')) ?></strong><small>Requieren revisión de la asignación</small></article>
    </div>
  </section>

  <?php if ($incomplete_computers !== []): ?>
  <section class="foundation-overview__section" aria-label="Computadores que requieren revisión">
    <div class="foundation-overview__section-heading">
      <div><h2>Computadores que requieren revisión</h2><p>Hasta 10 fichas con modelo, identificadores, ubicación o asignación pendientes.</p></div>
      <a class="btn btn-outline-primary" href="/front/computer.php">Buscar computadores</a>
    </div>
    <div class="table-responsive">
      <table class="table table-vcenter table-hover">
        <thead><tr><th>Computador</th><th>Información pendiente</th><th><span class="visually-hidden">Acción</span></th></tr></thead>
        <tbody>
        <?php foreach ($incomplete_computers as $computer): ?>
          <tr>
            <td><?= htmlescape($computer['name']) ?></td>
            <td><?= htmlescape(implode(', ', $computer['missing'])) ?></td>
            <td class="text-end"><a class="btn btn-sm btn-outline-primary" href="/front/computer.form.php?id=<?= htmlescape((string) $computer['id']) ?>">Revisar ficha</a></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </section>
  <?php endif; ?>
</main>
<?php
Html::footer();
