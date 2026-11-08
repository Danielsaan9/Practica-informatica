<?php

/// Class Group_KnowbaseItem
/// since version 0.83
class Group_KnowbaseItem extends CommonDBRelation
{
    // From CommonDBRelation
    public static ?string $itemtype_1 = KnowbaseItem::class;
    public static ?string $items_id_1          = 'knowbaseitems_id';
    public static ?string $itemtype_2 = Group::class;
    public static ?string $items_id_2          = 'groups_id';

    public static int $checkItem_2_Rights  = self::DONT_CHECK_ITEM_RIGHTS;
    public static bool $logs_for_item_2     = false;


    public function prepareInputForAdd($input)
    {
        // Avoid duplicate entry.
        // This application-level check is required because MySQL UNIQUE keys
        // do not deduplicate NULL values (NULL != NULL), so the DB constraint
        // alone cannot prevent duplicates when entities_id is NULL.
        if (
            countElementsInTable(
                static::getTable(),
                [
                    'WHERE' => [
                        'knowbaseitems_id' => $input['knowbaseitems_id'],
                        'groups_id'        => $input['groups_id'],
                        'entities_id'      => $input['entities_id'] ?? 0,
                    ],
                    'LIMIT' => 1,
                ]
            ) > 0
        ) {
            Session::addMessageAfterRedirect(
                __s('This target already exists for this article.'),
                false,
                ERROR
            );
            return false;
        }

        return parent::prepareInputForAdd($input);
    }

    /**
     * Get groups for a knowbaseitem
     *
     * @param int $knowbaseitems_id ID of the knowbaseitem
     *
     * @return array of groups linked to a knowbaseitem
     **/
    public static function getGroups($knowbaseitems_id)
    {
        global $DB;

        $groups = [];

        $iterator = $DB->request([
            'FROM'   => self::getTable(),
            'WHERE'  => [
                'knowbaseitems_id' => $knowbaseitems_id,
            ],
        ]);

        foreach ($iterator as $data) {
            $groups[$data['groups_id']][] = $data;
        }
        return $groups;
    }
}
