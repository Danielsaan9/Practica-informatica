<?php

namespace Glpi\System\Requirement;

/**
 * @since 9.5.0
 */
interface RequirementInterface
{
    /**
     * Get the title of the requirement.
     *
     * @return string
     */
    public function getTitle(): string;

    /**
     * Get the description of the requirement.
     *
     * @return null|string
     */
    public function getDescription(): ?string;

    /**
     * Get the validation messages of the requirement.
     *
     * @return string[]
     */
    public function getValidationMessages(): array;

    /**
     * Indicates if requirement is missing on system.
     *
     * @return bool
     */
    public function isMissing(): bool;

    /**
     * Indicates if requirement is considered as optional.
     *
     * @return bool
     */
    public function isOptional(): bool;

    /**
     * Indicates if requirement is recommended for security reasons.
     *
     * @return bool
     */
    public function isRecommendedForSecurity(): bool;

    /**
     * Indicates if requirement is considered as out of context
     * (i.e. system is not compatible).
     *
     * @return bool
     */
    public function isOutOfContext(): bool;

    /**
     * Indicates if requirement is validated on system.
     *
     * @return bool
     */
    public function isValidated(): bool;
}
