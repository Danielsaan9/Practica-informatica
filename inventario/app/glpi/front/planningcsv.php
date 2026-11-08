<?php

require_once(__DIR__ . '/_check_webserver_config.php');

use Glpi\Csv\CsvResponse;
use Glpi\Csv\PlanningCsv;

Session::checkRight(Planning::$rightname, READ);

$users_id = null;
$groups_id = (isset($_GET["gID"]) ? (int) $_GET['gID'] : 0);
$limititemtype = ($_GET['limititemtype'] ?? '');

if (!isset($_GET["uID"])) {
    if (
        ($uid = Session::getLoginUserID())
        && !Session::haveRight(Planning::$rightname, Planning::READALL)
    ) {
        $users_id = $uid;
    } else {
        $users_id = 0;
    }
} else {
    $users_id = (int) $_GET['uID'];
}

$user = new User();
$user->getFromDB(Session::getLoginUserID());

//// check if the request is valid: rights on uID / gID
// First check mine : user then groups
$ismine = false;
if ($user->getID() == $users_id) {
    $ismine = true;
}

// Check groups if have right to see
if (!$ismine && $groups_id !== 0) {
    $entities = Profile_User::getUserEntitiesForRight(
        $user->getID(),
        Planning::$rightname,
        Planning::READGROUP
    );
    $groups   = Group_User::getUserGroups($user->getID());
    foreach ($groups as $group) {
        if (
            $groups_id == $group['id']
            && in_array($group['entities_id'], $entities)
        ) {
            $ismine = true;
        }
    }
}

$canview = false;
// If not mine check global right
if (!$ismine) {
    // First check user
    $entities = Profile_User::getUserEntitiesForRight(
        $user->getID(),
        Planning::$rightname,
        Planning::READALL
    );
    if ($users_id) {
        $userentities = Profile_User::getUserEntities($user->getID());
        $intersect    = array_intersect($entities, $userentities);
        if (count($intersect)) {
            $canview = true;
        }
    }
    // Else check group
    if (!$canview && $groups_id) {
        $group = new Group();
        if ($group->getFromDB($groups_id)) {
            if (in_array($group->getEntityID(), $entities)) {
                $canview = true;
            }
        }
    }
}

if ($ismine || $canview) {
    CsvResponse::output(new PlanningCsv($users_id, $groups_id, $limititemtype));
}
