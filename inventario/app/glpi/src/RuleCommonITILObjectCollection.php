<?php

use function Safe\preg_match;

abstract class RuleCommonITILObjectCollection extends RuleCollection
{
    // From RuleCollection
    public bool $use_output_rule_process_as_next_input   = true;

    /**
     * @param int $entity (default 0)
     **/
    public function __construct($entity = 0)
    {
        parent::__construct();
        $this->entity = $entity;
    }

    /**
     * Get the ITIL Object itemtype that this rule collection is for
     * @return class-string<CommonITILObject> "Ticket", "Change" or "Problem"
     */
    public static function getItemtype(): string
    {
        // Return text between Rule and Collection is the current class name
        $matches = [];
        preg_match('/^Rule(.*)Collection$/', static::class, $matches);
        return $matches[1];
    }

    public static function canView(): bool
    {
        $rule_class = static::getRuleClassName();
        return Session::haveRightsOr(static::$rightname, [READ, $rule_class::PARENT]);
    }

    public function canList()
    {
        return static::canView();
    }

    public function preProcessPreviewResults($output)
    {
        $output = parent::preProcessPreviewResults($output);
        $itemtype = static::getItemtype();
        return $itemtype::showPreviewAssignAction($output);
    }

    public function showInheritedTab()
    {
        $rule_class = static::getRuleClassName();
        return (Session::haveRight(self::$rightname, $rule_class::PARENT) && ($this->entity));
    }

    public function showChildrensTab()
    {
        return (Session::haveRight(self::$rightname, READ)
            && (count($_SESSION['glpiactiveentities']) > 1));
    }

    public function prepareInputDataForProcess($input, $params)
    {
        $input['_groups_id_of_requester'] = [];
        // Get groups of users
        if (isset($input['_users_id_requester'])) {
            if (!is_array($input['_users_id_requester'])) {
                $requesters = [$input['_users_id_requester']];
            } else {
                $requesters = $input['_users_id_requester'];
            }
            foreach ($requesters as $uid) {
                foreach (Group_User::getUserGroups($uid) as $g) {
                    $input['_groups_id_of_requester'][$g['id']] = $g['id'];
                }
            }
        }

        // Required for rules on category code triggered by others rules
        if (isset($input['itilcategories_id']) && $input['itilcategories_id']) {
            $itilcategory = ITILCategory::getById($input['itilcategories_id']);
            if ($itilcategory) {
                $input['itilcategories_id_code'] = $itilcategory->fields['code'];
            }
        } elseif (isset($input['itilcategories_id'])) {
            // Category was cleared by a previous rule, do not keep a stale code
            unset($input['itilcategories_id_code']);
        }

        return $input;
    }
}
