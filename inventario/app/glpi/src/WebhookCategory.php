<?php

class WebhookCategory extends CommonTreeDropdown
{
    public bool $can_be_translated = true;

    public static function getTypeName($nb = 0)
    {
        return _n('Webhook category', 'Webhook categories', $nb);
    }

    public static function getIcon()
    {
        return "ti ti-tags";
    }
}
