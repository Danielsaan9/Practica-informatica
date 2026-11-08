<?php

class KnowbaseItem_Favorite extends CommonDBRelation
{
    // From CommonDBRelation
    public static ?string $itemtype_1 = KnowbaseItem::class;
    public static ?string $items_id_1 = 'knowbaseitems_id';
    public static ?string $itemtype_2 = User::class;
    public static ?string $items_id_2 = 'users_id';

    public static int $checkItem_1_Rights = self::HAVE_VIEW_RIGHT_ON_ITEM;
    public static int $checkItem_2_Rights = self::DONT_CHECK_ITEM_RIGHTS;
    public static bool $logs_for_item_1 = false;
    public static bool $logs_for_item_2 = false;

    public static function canCreate(): bool
    {
        // A favorite is stored per user: an anonymous public FAQ reader has nowhere to store it.
        return Session::getLoginUserID() !== false && parent::canCreate();
    }

    public function canCreateItem(): bool
    {
        // The root article is the knowledge base home, always one click away.
        if (KnowbaseItem::isRootId((int) ($this->fields['knowbaseitems_id'] ?? 0))) {
            return false;
        }

        return parent::canCreateItem();
    }

    public function prepareInputForAdd($input)
    {
        // Also covers the code paths that do not check `canCreateItem()`.
        if (KnowbaseItem::isRootId((int) ($input['knowbaseitems_id'] ?? 0))) {
            return false;
        }

        return parent::prepareInputForAdd($input);
    }

    public static function isFavoriteForCurrentUser(int $knowbaseitems_id): bool
    {
        $user_id = Session::getLoginUserID();
        if ($user_id === false) {
            return false;
        }

        return countElementsInTable(self::getTable(), [
            'knowbaseitems_id' => $knowbaseitems_id,
            'users_id'         => $user_id,
        ]) > 0;
    }
}
