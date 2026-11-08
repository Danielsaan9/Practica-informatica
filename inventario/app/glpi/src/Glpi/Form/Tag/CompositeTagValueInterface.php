<?php

namespace Glpi\Form\Tag;

interface CompositeTagValueInterface extends TagWithIdValueInterface
{
    /**
     * Extracts the ID to be remapped from the composite value.
     * Ex : "5:1" → "5"
     *
     * @param string $value The composite value
     * @return string The ID to be remapped
     */
    public function extractItemIdFromValue(string $value): string;

    /**
     * Rebuilds the composite value with a new ID.
     * Ex : ("5:1", "10") → "10:1"
     *
     * @param string $value The composite value
     * @param string $new_id The new ID to replace the old one
     * @return string The rebuilt composite value
     */
    public function rebuildValueWithMappedId(string $value, string $new_id): string;
}
