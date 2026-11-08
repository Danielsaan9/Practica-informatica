<?php

class AllAssets extends CommonGLPI
{
    public static function canView(): bool
    {
        return Session::getCurrentInterface() == "central";
    }

    public static function getHeaderParameters(): array
    {
        return [
            __('Global'),
            '',
            ...static::getSectorizedDetails(),
        ];
    }

    public static function getSectorizedDetails(): array
    {
        return ['assets', self::class];
    }

    public static function getTypeName($nb = 0)
    {
        return _n('Asset', 'Assets', $nb);
    }

    /**
     * @return string
     */
    public static function getIcon()
    {
        return 'ti ti-packages';
    }
}
