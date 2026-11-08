<?php

/**
 * @since 0.85
 */

header("Content-Type: text/html; charset=UTF-8");
Html::header_nocache();

Session::checkRight(Reservation::$rightname, ReservationItem::RESERVEANITEM);

// Make a select box
ReservationItem::ajaxDropdown($_POST);
