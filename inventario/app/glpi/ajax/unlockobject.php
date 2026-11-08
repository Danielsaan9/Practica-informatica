<?php

use Glpi\Exception\Http\AccessDeniedHttpException;

/**
 * @since 9.1
 */

// here we are going to try to unlock the given object
// url should be of the form: 'http://.../.../unlockobject.php?unlock=1&id=xxxxxx'
// or url should be of the form 'http://.../.../unlockobject.php?requestunlock=1&id=xxxxxx'
// to send notification to locker of object

header("Content-Type: text/html; charset=UTF-8");
Html::header_nocache();

$ret = 0;
if (isset($_POST['unlock']) && isset($_POST["id"])) {
    // then we may have something to unlock
    $ol = new ObjectLock();
    if ($ol->getFromDB($_POST["id"])) {
        $item = getItemForItemtype($ol->fields['itemtype']);

        $can_unlock = $ol->fields['users_id'] === Session::getLoginUserID()
            || (
                $item instanceof CommonDBTM
                && Session::haveRight($item::$rightname, UNLOCK)
                && $item->can($ol->fields['items_id'], UPDATE)
            );
        if (!$can_unlock) {
            throw new AccessDeniedHttpException();
        }

        if ($ol->deleteFromDB(true)) {
            Log::history(
                $ol->fields['items_id'],
                $ol->fields['itemtype'],
                [0, '', ''],
                0,
                Log::HISTORY_UNLOCK_ITEM
            );
            $ret = 1;
        }
    }
} elseif (
    isset($_POST['requestunlock'])
           && isset($_POST["id"])
) {
    // the we must ask for unlock
    $ol = new ObjectLock();
    if ($ol->getFromDB($_POST["id"])) {
        NotificationEvent::raiseEvent('unlock', $ol);
        $ret = 1;
    }
} elseif (
    isset($_GET['lockstatus'])
           && isset($_GET["id"])
) {
    $ol = new ObjectLock();
    if ($ol->getFromDB($_GET["id"])) {
        $ret = 1; // found = still locked
    }
}

echo $ret;
