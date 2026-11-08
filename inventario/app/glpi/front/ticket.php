<?php

require_once(__DIR__ . '/_check_webserver_config.php');

if (Session::getCurrentInterface() == "helpdesk") {
    Html::helpHeader(Ticket::getTypeName(Session::getPluralNumber()), 'tickets', 'ticket');
} else {
    Html::header(Ticket::getTypeName(Session::getPluralNumber()), '', "helpdesk", "ticket");
}

$refresh_callback = <<<JS
const container = $('div.ajax-container.search-display-data');
if (container.length > 0 && container.data('js_class') !== undefined) {
    container.data('js_class').getView().refreshResults();
} else {
    // Fallback when fluid search isn't initialized
    window.location.reload();
}
JS;

echo Html::manageRefreshPage(false, $refresh_callback);

Search::show('Ticket');

if (Session::getCurrentInterface() == "helpdesk") {
    Html::helpFooter();
} else {
    Html::footer();
}
