<?php

require_once(__DIR__ . '/_check_webserver_config.php');

use Glpi\Application\View\TemplateRenderer;
use Glpi\Exception\Http\AccessDeniedHttpException;

global $CFG_GLPI;

Session::checkCentralAccess();
Html::header(__('Search'));

if (!$CFG_GLPI['allow_search_global']) {
    throw new AccessDeniedHttpException();
}
if (isset($_GET["globalsearch"])) {
    $searchtext = trim($_GET["globalsearch"]);
    $no_result = [];

    echo "<div class='search_page search_page_global flex-row flex-wrap'>";
    foreach ($CFG_GLPI["globalsearch_types"] as $itemtype) {
        if (
            ($item = getItemForItemtype($itemtype))
            && $item->canView()
        ) {
            $_GET["reset"]        = 'reset';

            $params                 = Search::manageParams($itemtype, $_GET, false, true);
            $params["display_type"] = Search::GLOBAL_SEARCH;

            $count                  = count($params["criteria"]);

            $params["criteria"][$count]["field"]       = 'view';
            $params["criteria"][$count]["searchtype"]  = 'contains';
            $params["criteria"][$count]["value"]       = $searchtext;

            $data = Search::getDatas($itemtype, $params);
            if ($data['data']['count'] > 0) {
                echo "<div class='search-container w-100 disable-overflow-y' counter='" . (int) $data['data']['count'] . "'>";
                Search::displayData($data);
                echo "</div>";
            } else {
                $no_result[] = $itemtype::getTypeName(1);
            }
        }
    }

    // language=Twig
    echo TemplateRenderer::getInstance()->renderFromStringTemplate(<<<TWIG
        <div class="search-container w-100 disable-overflow-y" counter="0">
            <div class="ajax-container search-display-data">
                <div class="card card-sm mt-0 search-card">
                    <div class="card-header d-flex justify-content-between search-header pe-0">
                        <h2>{{ label }}</h2>
                    </div>
                    <ul>
                        {% for itemtype in no_result %}
                            <li>{{ itemtype }}</li>
                        {% endfor %}
                    </ul>
                </div>
            </div>
        </div>
TWIG, ['label' => __('Other searches with no item found'),'no_result' => $no_result]);
}

Html::footer();
