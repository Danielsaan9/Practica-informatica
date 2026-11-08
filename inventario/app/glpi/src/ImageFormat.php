<?php

class ImageFormat extends CommonDropdown
{
    public bool $can_be_translated = false;


    public static function getTypeName($nb = 0)
    {
        return _n('Image format', 'Image formats', $nb);
    }

    public static function getIcon()
    {
        return "ti ti-photo-cog";
    }

    public function cleanDBonPurge()
    {
        $this->deleteChildrenAndRelationsFromDb([
            Item_DeviceCamera_ImageFormat::class,
        ]);
    }
}
