<?php

namespace Glpi\ContentTemplates\Parameters\ParametersTypes;

use Glpi\ContentTemplates\Parameters\TemplatesParametersInterface;

/**
 * Interface for parameters types.
 *
 * @since 10.0.0
 */
interface ParameterTypeInterface
{
    /**
     * To be defined in each subclasses, convert the parameter data into an array
     * that can be shared to the client side code as json and used for autocompletion.
     *
     * @return array
     */
    public function compute(): array;

    /**
     * Label to use for this parameter's documentation
     *
     * @return string
     */
    public function getDocumentationLabel(): string;

    /**
     * Recommended usage (twig code) to use for this parameter's documentation
     *
     * @param string|null $parent
     *
     * @return string
     */
    public function getDocumentationUsage(?string $parent = null): string;

    /**
     * Reference to others parameters for this parameter's documentation
     *
     * @return TemplatesParametersInterface|null
     */
    public function getDocumentationReferences(): ?TemplatesParametersInterface;

    /**
     * Field name for this parameter's documentation
     *
     * @return string
     */
    public function getDocumentationField(): string;
}
