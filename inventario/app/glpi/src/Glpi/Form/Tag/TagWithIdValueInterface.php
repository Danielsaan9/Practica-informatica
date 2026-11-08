<?php

namespace Glpi\Form\Tag;

/**
 * If a tag reference an ID as its value, it must also implement this interface
 * to indicate the target class that must be instanciate the value.
 */
interface TagWithIdValueInterface
{
    /**
     * Indicate the itemtype referenced by the tag "value" property.
     * @return class-string<\CommonDBTM>
     */
    public function getItemtype(): string;
}
