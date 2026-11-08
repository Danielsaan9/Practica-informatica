<?php

header("Content-Type: text/html; charset=UTF-8");
Html::header_nocache();

Session::checkRightsOr(Notification::$rightname, [CREATE, UPDATE]);

NotificationEvent::dropdownEvents($_POST['itemtype']);
