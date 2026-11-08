<?php

class ITILValidationTemplate_Target extends CommonDBRelation
{
    public bool $dohistory = true;

    public static ?string $itemtype_1 = ITILValidationTemplate::class;
    public static ?string $items_id_1 = 'itilvalidationtemplates_id';
    public static bool $take_entity_1 = false;

    public static ?string $itemtype_2 = 'itemtype';
    public static ?string $items_id_2 = 'items_id';
    public static bool $take_entity_2 = true;

    public static function getTypeName($nb = 0)
    {
        return _n('Approval template target', 'Approval template targets', $nb);
    }

    /**
     * @param int $itilvalidationtemplates_id
     *
     * @return array
     */
    public static function getTargets($itilvalidationtemplates_id)
    {
        return (new self())->find([
            'itilvalidationtemplates_id' => $itilvalidationtemplates_id,
        ]);
    }
}
