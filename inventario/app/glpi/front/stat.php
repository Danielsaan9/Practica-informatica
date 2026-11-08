<?php

require_once(__DIR__ . '/_check_webserver_config.php');

Html::header(__('Statistics'), '', "helpdesk", "stat");

Session::checkRight(Stat::$rightname, READ);

Stat::title();

Html::footer();
