<?php

class ImageResolution extends CommonDropdown
{
    public bool $can_be_translated = false;

    public static function getTypeName($nb = 0)
    {
        return _nx('image', 'Resolution', 'Resolutions', $nb);
    }

    public static function getIcon()
    {
        return "fas fa-expand";
    }

    public function cleanDBonPurge()
    {
        $this->deleteChildrenAndRelationsFromDb([
            Item_DeviceCamera_ImageResolution::class,
        ]);
    }
}
