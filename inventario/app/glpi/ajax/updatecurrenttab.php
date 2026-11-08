<?php

// Manage tabs
if (isset($_GET['itemtype']) && isset($_GET['tab_key'])) {
    Session::setActiveTab($_GET['itemtype'], $_GET['tab_key']);
}
