<?php

require_once(__DIR__ . '/_check_webserver_config.php');

if (!isset($_GET["reservationitems_id"])) {
    $_GET["reservationitems_id"] = 0;
}

if (Session::getCurrentInterface() == "helpdesk") {
    Html::helpHeader(
        sprintf(
            '%s - %s',
            Reservation::getTypeName(Session::getPluralNumber()),
            __('Simplified interface')
        )
    );
} else {
    Html::header(Reservation::getTypeName(Session::getPluralNumber()), '', "tools", "reservationitem");
}

Reservation::showCalendar((int) $_GET["reservationitems_id"]);

if (Session::getCurrentInterface() == "helpdesk") {
    Html::helpFooter();
} else {
    Html::footer();
}
