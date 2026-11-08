<?php

/// Class Entity_KnowbaseItem
/// since version 0.83
class Entity_KnowbaseItem extends CommonDBRelation
{
    // From CommonDBRelation
    public static ?string $itemtype_1 = KnowbaseItem::class;
    public static ?string $items_id_1          = 'knowbaseitems_id';
    public static ?string $itemtype_2 = Entity::class;
    public static ?string $items_id_2          = 'entities_id';

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
     * Get entities for a knowbaseitem
     *
     * @param int $knowbaseitems_id ID of the knowbaseitem
     *
     * @return array of entities linked to a knowbaseitem
     **/
    public static function getEntities($knowbaseitems_id)
    {
        global $DB;

        $ent   = [];

        $iterator = $DB->request([
            'FROM'   => self::getTable(),
            'WHERE'  => [
                'knowbaseitems_id' => $knowbaseitems_id,
            ],
        ]);

        foreach ($iterator as $data) {
            $ent[$data['entities_id']][] = $data;
        }
        return $ent;
    }
}
