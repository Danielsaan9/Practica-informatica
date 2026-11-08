<?php

namespace Glpi\Api\Deprecated;

/**
 * @since 9.5
 */

interface DeprecatedInterface
{
    /**
     * Get the deprecated itemtype
     *
     * @return string
     */
    public function getType(): string;

    /**
     * Convert current hateoas to deprecated hateoas
     *
     * @param array $hateoas
     * @return array
     */
    public function mapCurrentToDeprecatedHateoas(array $hateoas): array;

    /**
     * Convert current fields to deprecated fields
     *
     * @param array $fields
     * @return array
     */
    public function mapCurrentToDeprecatedFields(array $fields): array;

    /**
     * Convert current searchoptions to deprecated searchoptions
     *
     * @param array $soptions
     * @return array
     */
    public function mapCurrentToDeprecatedSearchOptions(array $soptions): array;

    /**
     * Convert deprecated fields to current fields
     *
     * @param object $fields
     * @return object
     */
    public function mapDeprecatedToCurrentFields(object $fields): object;

    /**
     * Convert deprecated search criteria to current search criteria
     *
     * @param array $criteria
     * @return array
     */
    public function mapDeprecatedToCurrentCriteria(array $criteria): array;
}
