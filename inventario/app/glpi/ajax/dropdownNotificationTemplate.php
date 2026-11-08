<?php

header("Content-Type: text/html; charset=UTF-8");
Html::header_nocache();

Session::checkRight(Notification::$rightname, UPDATE);

NotificationTemplate::dropdownTemplates('notificationtemplates_id', $_POST['itemtype']);
