<?php

class ClusterType extends CommonType
{
    public static function getTypeName($nb = 0)
    {
        return _n('Cluster type', 'Cluster types', $nb);
    }
}
