<?php

use function Safe\json_encode;

Session::checkRightsOr(Reservation::$rightname, [READ, ReservationItem::RESERVEANITEM]);

if (!isset($_REQUEST["action"])) {
    return;
}

if ($_REQUEST["action"] == "get_events") {
    header("Content-Type: application/json; charset=UTF-8");
    echo json_encode(Reservation::getEvents($_REQUEST));
    return;
}

Session::checkRight(Reservation::$rightname, ReservationItem::RESERVEANITEM);

if ($_REQUEST["action"] == "get_resources") {
    header("Content-Type: application/json; charset=UTF-8");
    echo json_encode(Reservation::getResources());
    return;
}

if (($_POST['action'] ?? null) === "update_event") {
    $result = Reservation::updateEvent($_REQUEST);
    echo json_encode(['result' => $result]);
    return;
}

Html::header_nocache();
header("Content-Type: text/html; charset=UTF-8");

if ($_REQUEST["action"] == "add_edit_reservation_fromselect") {
    $reservation = new Reservation();
    $reservation->showForm($_REQUEST['id'], $_REQUEST);
}
