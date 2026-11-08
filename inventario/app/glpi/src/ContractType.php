<?php

/// Class ContractType
class ContractType extends CommonType
{
    public static function getTypeName($nb = 0)
    {
        return _n('Contract type', 'Contract types', $nb);
    }
}
