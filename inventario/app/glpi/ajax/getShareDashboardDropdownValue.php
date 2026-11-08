<?php

use Glpi\Dashboard\Dashboard;

include(__DIR__ . '/getAbstractRightDropdownValue.php');

// Only users who can update dashboards are allowed to use the "share dashboard" feature
// Users without this right shouldn't be allowed to read this dropdown values
Session::checkRight(Dashboard::$rightname, UPDATE);

show_rights_dropdown(ShareDashboardDropdown::class);
