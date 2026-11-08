<?php

trait ITILSubItemRights
{
    public const SEEPUBLIC          = 1;
    public const UPDATEMY           = 2;
    public const ADDMY              = 4;
    public const UPDATEALL          = 1024;
    public const ADD_AS_GROUP       = 2048;
    public const ADDALLITEM         = 4096;
    public const SEEPRIVATE         = 8192;
    public const ADD_AS_OBSERVER    = 16384;
    public const ADD_AS_TECHNICIAN  = 32768;

    public const SEEPRIVATEGROUPS         = 65536;

    public function getRights($interface = 'central')
    {

        $values = parent::getRights();
        unset($values[UPDATE], $values[CREATE], $values[READ]);

        if ($interface == 'central') {
            $values[self::UPDATEALL] = __('Update all');
            $values[self::ADDALLITEM] = __('Add to all items');
            $values[self::SEEPRIVATE] = __('See private ones');
            $values[self::SEEPRIVATEGROUPS] = __('See private of my groups');
        }

        $values[self::ADD_AS_GROUP] = [
            'short' => __('Add (associated groups)'),
            'long'  => __('Add to items of associated groups'),
        ];
        $values[self::UPDATEMY] = __('Update (author)');
        $values[self::ADDMY] = [
            'short' => __('Add (requester)'),
            'long'  => __('Add to items (requester)'),
        ];
        $values[self::ADD_AS_OBSERVER] = [
            'short' => __('Add (observer)'),
            'long'  => __('Add to items (observer)'),
        ];
        $values[self::ADD_AS_TECHNICIAN] = [
            'short' => __('Add (technician)'),
            'long'  => __('Add to items (technician)'),
        ];
        $values[self::SEEPUBLIC] = __('See public ones');

        if ($interface == 'helpdesk') {
            unset($values[PURGE]);
        }

        return $values;
    }
}
