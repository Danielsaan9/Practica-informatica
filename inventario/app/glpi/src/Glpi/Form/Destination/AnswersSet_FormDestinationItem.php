<?php

namespace Glpi\Form\Destination;

use CommonDBRelation;
use Glpi\Form\AnswersSet;

final class AnswersSet_FormDestinationItem extends CommonDBRelation
{
    /**
     * Item 1 is an AnswersSet object
     */
    public static ?string $itemtype_1 = AnswersSet::class;
    public static ?string $items_id_1 = 'forms_answerssets_id';

    /**
     * Item 2 is any common DBTM item
     */
    public static ?string $itemtype_2 = 'itemtype';
    public static ?string $items_id_2 = 'items_id';
}
