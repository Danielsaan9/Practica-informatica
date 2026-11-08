<?php

require_once __DIR__ . '/../../../inc/includes.php';

Session::checkCentralAccess();
Session::checkRight(Config::$rightname, UPDATE);

if (isset($_POST['remove_market_token'])) {
    if (PluginFundacionluzValuation::saveMarketToken('')) {
        Session::addMessageAfterRedirect('Credencial eliminada.', false, INFO);
    } else {
        Session::addMessageAfterRedirect('No se pudo eliminar la credencial.', false, ERROR);
    }
    Html::back();
}

if (isset($_POST['save_market_token'])) {
    $token = trim((string) ($_POST['market_token'] ?? ''));
    if ($token === '' && PluginFundacionluzValuation::hasStoredMarketToken()) {
        Session::addMessageAfterRedirect('La credencial guardada se conservó. Si no se puede consultar, revisa la llave glpicrypt.key.', false, INFO);
    } elseif (mb_strlen($token) > 4096) {
        Session::addMessageAfterRedirect('La credencial es demasiado larga.', false, ERROR);
    } elseif (PluginFundacionluzValuation::saveMarketToken($token)) {
        Session::addMessageAfterRedirect($token === '' ? 'Credencial eliminada.' : 'Credencial guardada cifrada.', false, INFO);
    } else {
        Session::addMessageAfterRedirect('No se pudo guardar la credencial. Revisa que exista la llave glpicrypt.key de GLPI.', false, ERROR);
    }
    Html::back();
}

$has_token = PluginFundacionluzValuation::getMarketToken() !== '';
$has_stored_token = PluginFundacionluzValuation::hasStoredMarketToken();
Html::header('Configuración de valores de mercado', $_SERVER['PHP_SELF'], 'config', 'plugins');
?>
<main class="container-fluid foundation-economy">
  <header class="foundation-economy__header">
    <div>
      <h1>Conexión de precios de mercado</h1>
      <p>La consulta usa publicaciones nuevas de Mercado Libre Chile (MLC) y guarda la mediana de resultados comparables en CLP.</p>
    </div>
  </header>
  <section class="foundation-economy__section">
    <h2>Credencial de API</h2>
    <p>Registra una aplicación en Mercado Libre Developers y pega aquí un Access Token vigente. El token se cifra usando la llave de seguridad de GLPI y nunca se muestra después de guardarlo.</p>
    <p><a href="https://developers.mercadolibre.com.ar/autenticacion-y-autorizacion" target="_blank" rel="noopener noreferrer">Documentación oficial de autenticación de Mercado Libre</a></p>
    <p>Consulta de mercado: <strong><?= extension_loaded('curl') ? 'cURL disponible en PHP' : 'cURL no está disponible en PHP' ?></strong><?= extension_loaded('curl') ? '' : ' · Revisa la extensión cURL de XAMPP antes de consultar precios.' ?></p>
    <p>Llave de cifrado de GLPI: <strong><?= PluginFundacionluzValuation::isSecurityKeyReady() ? 'disponible' : 'no disponible' ?></strong><?= PluginFundacionluzValuation::isSecurityKeyReady() ? '' : ' · Configura config/glpicrypt.key antes de guardar tokens.' ?></p>
    <p>Estado de credencial: <strong><?= $has_token ? 'Credencial configurada y descifrable' : ($has_stored_token ? 'Hay una credencial guardada, pero no se pudo descifrar' : 'Pendiente de credencial') ?></strong></p>
    <form method="post" class="foundation-economy__form">
      <label>Access Token<input class="form-control" type="password" name="market_token" autocomplete="new-password" maxlength="4096" placeholder="<?= $has_token ? 'Deja vacío para conservar la credencial' : 'APP_USR-…' ?>"></label>
      <button class="btn btn-primary" type="submit" name="save_market_token" value="1">Guardar credencial</button>
    </form>
    <?php if ($has_stored_token): ?>
    <form method="post" class="mt-3">
      <input type="hidden" name="market_token" value="">
      <button class="btn btn-outline-danger" type="submit" name="remove_market_token" value="1">Eliminar credencial</button>
    </form>
    <?php endif; ?>
  </section>
</main>
<?php
Html::footer();
