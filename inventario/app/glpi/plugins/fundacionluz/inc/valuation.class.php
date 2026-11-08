<?php

class PluginFundacionluzValuation extends CommonGLPI
{
    private const MARKET_CONTEXT = 'plugin:fundacionluz';
    private const MARKET_TOKEN_KEY = 'mercadolibre_access_token';
    private const COMPUTER_LIFE_YEARS = 6;
    private const MARKET_ESTIMATE_MAX_AGE_DAYS = 90;

    public static function getTypeName($nb = 0): string
    {
        return 'Resumen Fundación Luz';
    }

    public static function getMenuName(): string
    {
        return self::getTypeName();
    }

    public static function getMenuContent(): array|false
    {
        if (!Session::haveRight(Computer::$rightname, READ)) {
            return false;
        }

        return [
            'title' => self::getMenuName(),
            'page'  => '/plugins/fundacionluz/front/overview.php',
            'icon'  => 'ti ti-chart-bar',
        ];
    }

    public function getTabNameForItem(CommonGLPI $item, $withtemplate = 0)
    {
        if ($item instanceof Computer && $item->getID() > 0 && Session::haveRight(Computer::$rightname, READ)) {
            return self::createTabEntry('Valor económico', 0, Computer::class, 'ti ti-currency-dollar');
        }

        return '';
    }

    public static function displayTabContentForItem(CommonGLPI $item, $tabnum = 1, $withtemplate = 0)
    {
        if (!($item instanceof Computer) || !Session::haveRight(Computer::$rightname, READ)) {
            return false;
        }

        global $DB;
        $purchase_value = null;
        $purchase_date = null;
        $infocom = new Infocom();
        if ($infocom->getFromDBforDevice(Computer::class, $item->getID())) {
            $amount = (float) ($infocom->fields['value'] ?? 0);
            $raw_date = $infocom->fields['buy_date'] ?? null;
            if ($amount > 0) {
                $purchase_value = $amount;
            }
            if (is_string($raw_date) && $raw_date !== '' && $raw_date !== '0000-00-00') {
                $purchase_date = $raw_date;
            }
        }

        $model_id = (int) ($item->fields['computermodels_id'] ?? 0);
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

        $estimate = null;
        if ($model_id > 0) {
            foreach ($DB->request([
                'FROM'  => 'glpi_plugin_fundacionluz_valuations',
                'WHERE' => ['itemtype' => Computer::class, 'models_id' => $model_id],
                'LIMIT' => 1,
            ]) as $row) {
                $estimate = $row;
                break;
            }
        }

        $book_value = null;
        $purchase_date_in_future = false;
        if ($purchase_value !== null && $purchase_date !== null) {
            try {
                $bought_at = new DateTimeImmutable($purchase_date);
                $today = new DateTimeImmutable('today');
                if ($bought_at > $today) {
                    $purchase_date_in_future = true;
                } else {
                    $days = $bought_at->diff($today)->days;
                    $life_days = self::COMPUTER_LIFE_YEARS * 365.25;
                    $book_value = max(0, $purchase_value * (1 - ($days / $life_days)));
                }
            } catch (Throwable) {
                $book_value = null;
            }
        }

        $currency = static fn(float $value): string => '$' . number_format($value, 0, ',', '.') . ' CLP';
        echo '<section class="foundation-economy__section">';
        echo '<h3>Valor y depreciación estimada</h3>';
        echo '<p>Estimación lineal referencial a 6 años, calculada desde la fecha de compra. No reemplaza la política contable de la Fundación.</p>';
        echo '<div class="foundation-economy__totals">';
        $purchase_status = $purchase_date_in_future ? 'La fecha de compra es futura; revisa el registro.' : ($purchase_date === null ? 'Fecha de compra no registrada' : 'Compra: ' . htmlescape(Html::convDate($purchase_date)));
        echo '<article><span>Costo registrado</span><strong>' . ($purchase_value === null ? 'Sin dato' : htmlescape($currency($purchase_value))) . '</strong><small>' . $purchase_status . '</small></article>';
        echo '<article><span>Valor contable lineal estimado hoy</span><strong>' . ($book_value === null ? 'No calculable' : htmlescape($currency($book_value))) . '</strong><small>Calculado al ' . htmlescape(date('d/m/Y')) . ' · SII: computadores, vida útil normal de 6 años (<a href="https://www.sii.cl/pagina/valores/bienes/tabla_vida_enero.htm" target="_blank" rel="noopener noreferrer">tabla oficial</a>)</small></article>';
        if ($estimate !== null) {
            $estimate_date = new DateTimeImmutable((string) $estimate['estimate_date']);
            $today = new DateTimeImmutable('today');
            $estimate_age = $estimate_date > $today
                ? self::MARKET_ESTIMATE_MAX_AGE_DAYS + 1
                : $estimate_date->diff($today)->days;
            $estimate_freshness = $estimate_age > self::MARKET_ESTIMATE_MAX_AGE_DAYS ? ' · Referencia desactualizada; conviene renovarla' : '';
            echo '<article><span>Reposición de mercado</span><strong>' . htmlescape($currency((float) $estimate['replacement_value'])) . '</strong><small>' . htmlescape((string) $estimate['source']) . ' · ' . htmlescape(Html::convDate((string) $estimate['estimate_date'])) . htmlescape($estimate_freshness) . '</small></article>';
        } else {
            echo '<article><span>Reposición de mercado</span><strong>Pendiente</strong><small>Sin precio comparable guardado para este modelo</small></article>';
        }
        echo '</div>';

        if ($model_name !== '') {
            echo '<p class="mt-3">Modelo consultable: ' . htmlescape(trim($manufacturer_name . ' ' . $model_name)) . '</p>';
        }
        if (Session::haveRight(Computer::$rightname, UPDATE)) {
            echo '<form method="post" action="/plugins/fundacionluz/front/refresh.php" class="mt-3">';
            echo '<input type="hidden" name="computers_id" value="' . htmlescape((string) $item->getID()) . '">';
            echo '<button class="btn btn-outline-primary" type="submit"' . ($model_name === '' ? ' disabled' : '') . '>Consultar referencia de mercado</button>';
            echo '</form>';
        }
        echo '</section>';

        return true;
    }

    public static function getMarketToken(): string
    {
        $key = new GLPIKey();
        if ($key->hasReadErrors()) {
            return '';
        }

        $encrypted = (string) Config::getConfigurationValue(self::MARKET_CONTEXT, self::MARKET_TOKEN_KEY);
        if ($encrypted === '') {
            return '';
        }

        return (string) $key->decrypt($encrypted);
    }

    public static function hasStoredMarketToken(): bool
    {
        return (string) Config::getConfigurationValue(self::MARKET_CONTEXT, self::MARKET_TOKEN_KEY) !== '';
    }

    public static function isSecurityKeyReady(): bool
    {
        return !(new GLPIKey())->hasReadErrors();
    }

    public static function getMarketEstimateMaxAgeDays(): int
    {
        return self::MARKET_ESTIMATE_MAX_AGE_DAYS;
    }

    public static function saveMarketToken(string $token): bool
    {
        $key = new GLPIKey();
        if ($key->hasReadErrors()) {
            return false;
        }

        $encrypted = $token === '' ? '' : $key->encrypt($token);
        if ($token !== '' && $encrypted === '') {
            return false;
        }

        Config::setConfigurationValues(self::MARKET_CONTEXT, [self::MARKET_TOKEN_KEY => $encrypted]);
        return true;
    }

    public static function fetchMarketEstimate(string $query): array
    {
        $token = self::getMarketToken();
        if ($token === '') {
            return ['error' => 'Falta configurar la credencial de Mercado Libre en la configuración del plugin.'];
        }
        if (!function_exists('curl_init')) {
            return ['error' => 'La extensión cURL de PHP no está disponible.'];
        }

        $url = 'https://api.mercadolibre.com/sites/MLC/search?q=' . rawurlencode($query) . '&limit=50';
        $curl = curl_init($url);
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_PROTOCOLS => CURLPROTO_HTTPS,
            CURLOPT_HTTPHEADER => ['Authorization: Bearer ' . $token, 'Accept: application/json'],
            CURLOPT_USERAGENT => 'FundacionLuz-GLPI/1.1',
        ]);
        $body = curl_exec($curl);
        $status = (int) curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
        $curl_error = curl_error($curl);
        curl_close($curl);

        if (!is_string($body) || $status < 200 || $status >= 300) {
            return ['error' => $status === 401 ? 'Mercado Libre rechazó la credencial. Actualízala en la configuración.' : 'No fue posible consultar Mercado Libre (' . ($status ?: 'red') . '). ' . mb_substr($curl_error, 0, 120)];
        }

        try {
            $data = json_decode($body, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException) {
            return ['error' => 'La API devolvió una respuesta que no se pudo interpretar.'];
        }

        $tokens = array_values(array_filter(preg_split('/[^\pL\pN]+/u', mb_strtolower($query)) ?: [], static fn(string $part): bool => mb_strlen($part) >= 3));
        $prices = [];
        foreach (($data['results'] ?? []) as $result) {
            $title = mb_strtolower((string) ($result['title'] ?? ''));
            $has_model_tokens = $tokens !== [] && count(array_filter($tokens, static fn(string $part): bool => str_contains($title, $part))) >= count($tokens);
            $price = (float) ($result['price'] ?? 0);
            if ($has_model_tokens && ($result['condition'] ?? '') === 'new' && $price > 0 && ($result['currency_id'] ?? '') === 'CLP') {
                $prices[] = $price;
            }
        }

        if ($prices === []) {
            return ['error' => 'No hay publicaciones comparables en CLP para este modelo.'];
        }

        sort($prices, SORT_NUMERIC);
        $count = count($prices);
        $middle = intdiv($count, 2);
        $median = $count % 2 ? $prices[$middle] : ($prices[$middle - 1] + $prices[$middle]) / 2;

        return ['value' => $median, 'count' => $count, 'date' => date('Y-m-d')];
    }
}
