<?php

class ITILReminder extends CommonDBChild
{
    // From CommonDBTM
    public bool $dohistory = true;

    public static string $itemtype = 'itemtype'; // Class name or field name (start with itemtype) for link to Parent
    public static string $items_id = 'items_id'; // Field name

    public static function getTypeName($nb = 0)
    {
        return _n('Automatic reminder', 'Automatic reminders', $nb);
    }

    public function getPendingReason(): PendingReason
    {
        $pending_reason = new PendingReason();
        $pending_reason->getFromDB($this->fields['pendingreasons_id']);
        return $pending_reason;
    }

    public static function getIcon()
    {
        return "ti ti-refresh-alert";
    }
}
