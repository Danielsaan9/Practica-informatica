<?php

require_once(__DIR__ . '/_check_webserver_config.php');

Session::checkRightsOr(Reservation::$rightname, [READ, ReservationItem::RESERVEANITEM]);

if (Session::getCurrentInterface() == "helpdesk") {
    Html::helpHeader(
        sprintf(
            '%s - %s',
            Reservation::getTypeName(Session::getPluralNumber()),
            __('Simplified interface')
        ),
        'reservation'
    );
} else {
    Html::header(Reservation::getTypeName(Session::getPluralNumber()), '', "tools", "reservationitem");
}

$res = new ReservationItem();
$res->display($_GET);

if (isset($_POST['submit'])) {
    $_SESSION['glpi_saved']['ReservationItem'] = $_POST;
} else {
    unset($_SESSION['glpi_saved']['ReservationItem']);
}

if (Session::getCurrentInterface() == "helpdesk") {
    Html::helpFooter();
} else {
    Html::footer();
}
