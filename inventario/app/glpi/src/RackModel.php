<?php

/// Class RackModel
class RackModel extends CommonDropdown
{
    public array $additional_fields_for_dictionnary = ['manufacturer'];

    public static function getTypeName($nb = 0)
    {
        return _n('Rack model', 'Rack models', $nb);
    }

    public static function getIcon()
    {
        return Rack::getIcon();
    }
}
