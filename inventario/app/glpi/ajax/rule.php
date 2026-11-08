<?php

use Glpi\Exception\Http\AccessDeniedHttpException;

switch ($_POST['action']) {
    case "move_rule":
        $rule_collection = getItemForItemtype($_POST['collection_classname']);
        if ($rule_collection instanceof RuleCollection) {
            if (!$rule_collection->canUpdate()) {
                throw new AccessDeniedHttpException();
            }
            $rule_collection->moveRule((int) $_POST['rule_id'], (int) $_POST['ref_id'], $_POST['sort_action']);
        }
        break;
}
