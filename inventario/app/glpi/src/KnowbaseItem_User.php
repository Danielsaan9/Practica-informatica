<?php

/// Class KnowbaseItem_User
/// since version 0.83
class KnowbaseItem_User extends CommonDBRelation
{
    // From CommonDBRelation
    public static ?string $itemtype_1 = KnowbaseItem::class;
    public static ?string $items_id_1          = 'knowbaseitems_id';
    public static ?string $itemtype_2 = User::class;
    public static ?string $items_id_2          = 'users_id';

    public static int $checkItem_2_Rights  = self::DONT_CHECK_ITEM_RIGHTS;
    public static bool $logs_for_item_2     = false;


    public function prepareInputForAdd($input)
    {
        // Avoid duplicate entry
        if (
            countElementsInTable(
                static::getTable(),
                [
                    'WHERE' => [
                        'knowbaseitems_id' => $input['knowbaseitems_id'],
                        'users_id'         => $input['users_id'],
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
     * Get users for a knowbaseitem
     *
     * @param int $knowbaseitems_id  ID of the knowbaseitem
     *
     * @return array of users linked to a knowbaseitem
     **/
    public static function getUsers($knowbaseitems_id)
    {
        global $DB;

        $users = [];

        $iterator = $DB->request([
            'FROM'   => self::getTable(),
            'WHERE'  => [
                'knowbaseitems_id' => $knowbaseitems_id,
            ],
        ]);

        foreach ($iterator as $data) {
            $users[$data['users_id']][] = $data;
        }
        return $users;
    }
}
