<?php

namespace Glpi\Form\QuestionType;

/**
 * Interface that must be implemented by all available questions types
 */
interface QuestionTypeCategoryInterface
{
    public function getLabel(): string;

    /**
     * Return an icon class (e.g. 'ti ti-user').
     */
    public function getIcon(): string;

    /**
     * Used for ordering categories (lower weight will be displayed first).
     */
    public function getWeight(): int;
}
