<?php

use Glpi\Exception\Http\AccessDeniedHttpException;

use function Safe\json_decode;
use function Safe\json_encode;

Session::checkCentralAccess();

if (!isset($_REQUEST["action"])) {
    return;
}

$extevent = new PlanningExternalEvent();

if ($_REQUEST["action"] == "get_events") {
    header("Content-Type: application/json; charset=UTF-8");
    if (isset($_REQUEST['state_done'])) {
        $_REQUEST['state_done'] = filter_var($_REQUEST['state_done'], FILTER_VALIDATE_BOOLEAN);
    }
    echo json_encode(Planning::constructEventsArray($_REQUEST));
    return;
}

if (($_POST["action"] ?? null) == "update_event_times") {
    echo Planning::updateEventTimes($_POST);
    return;
}

if (($_POST["action"] ?? null) == "view_changed") {
    Planning::viewChanged($_POST['view']);
    return;
}

if (($_POST["action"] ?? null) == "clone_event") {
    $extevent->checkGlobal(CREATE);
    echo Planning::cloneEvent($_POST['event']);
    return;
}

if (($_POST["action"] ?? null) == "delete_event") {
    $event = $_POST['event'];
    // rights check is done inside `Planning::deleteEvent()`, depending on the event itemtype
    echo Planning::deleteEvent($event);
    return;
}

if ($_REQUEST["action"] == "get_externalevent_template") {
    $key = 'planningexternaleventtemplates_id';
    if (
        isset($_POST[$key])
        && $_POST[$key] > 0
    ) {
        $template = new PlanningExternalEventTemplate();
        if (!$template->getFromDB((int) $_POST[$key]) || !$template->canViewItem()) {
            throw new AccessDeniedHttpException();
        }

        // Decode rrule field only if not empty
        if (!empty($template->fields['rrule'])) {
            $template->fields['rrule'] = json_decode($template->fields['rrule'], true);
        }
        header("Content-Type: application/json; charset=UTF-8");
        echo json_encode($template->fields, JSON_NUMERIC_CHECK);
        return;
    }
}

Html::header_nocache();
header("Content-Type: text/html; charset=UTF-8");

if ($_REQUEST["action"] == "add_event_fromselect") {
    Planning::showAddEventForm($_REQUEST);
}

if ($_REQUEST["action"] == "add_event_sub_form") {
    Planning::showAddEventSubForm($_REQUEST);
}

if ($_REQUEST["action"] == "add_planning_form") {
    Planning::showAddPlanningForm();
}

if ($_REQUEST["action"] == "add_user_form") {
    Planning::showAddUserForm();
}

if ($_REQUEST["action"] == "add_group_users_form") {
    Planning::showAddGroupUsersForm();
}

if ($_REQUEST["action"] == "add_group_form") {
    Planning::showAddGroupForm();
}

if ($_REQUEST["action"] == "add_external_form") {
    Planning::showAddExternalForm();
}

if ($_REQUEST["action"] == "add_event_classic_form") {
    Planning::showAddEventClassicForm($_REQUEST);
}

if ($_REQUEST["action"] == "edit_event_form") {
    Planning::editEventForm($_REQUEST);
}

if (($_POST["action"] ?? null) == "toggle_filter") {
    Planning::toggleFilter($_POST);
}

if (($_POST["action"] ?? null) == "color_filter") {
    Planning::colorFilter($_POST);
}

if (($_POST["action"] ?? null) == "delete_filter") {
    Planning::deleteFilter($_POST);
}
