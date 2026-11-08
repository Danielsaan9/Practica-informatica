<?php

namespace Glpi\ContentTemplates\Parameters\ParametersTypes;

/**
 * Define the base interface for parameters types.
 *
 * @since 10.0.0
 */
abstract class AbstractParameterType implements ParameterTypeInterface
{
    /**
     * The parameter key that need to be used to retrieve its value in a template.
     *
     */
    protected string $key;

    /**
     * The parameter label, to be displayed in the client side autocompletion.
     *
     */
    protected string $label;

    /**
     * @param string  $key     Key to access this value
     * @param string  $label   Label to display in the autocompletion widget
     */
    public function __construct(string $key, string $label)
    {
        $this->key = $key;
        $this->label = $label;
    }

    public function getDocumentationField(): string
    {
        return $this->key;
    }

    public function getDocumentationLabel(): string
    {
        return $this->label;
    }
}
