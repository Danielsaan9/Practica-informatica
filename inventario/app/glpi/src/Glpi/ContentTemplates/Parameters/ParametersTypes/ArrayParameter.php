<?php

namespace Glpi\ContentTemplates\Parameters\ParametersTypes;

use Glpi\ContentTemplates\Parameters\TemplatesParametersInterface;

/**
 * ArrayParmameter represent a template parameter that contains multiple objets
 * of the same types.
 * For example the requesters of a tickets or the users in a group.
 *
 * @since 10.0.0
 */
class ArrayParameter extends AbstractParameterType
{
    /**
     * Parameters of each item contained in this array.
     *
     */
    protected TemplatesParametersInterface $template_parameters;

    /**
     * @param string                       $key        Key to access this value
     * @param TemplatesParametersInterface $parameters Parameters of each item contained in this array
     * @param string                       $label      Label to display in the autocompletion widget
     */
    public function __construct(
        string $key,
        TemplatesParametersInterface $parameters,
        string $label
    ) {
        parent::__construct($key, $label);
        $this->template_parameters = $parameters;
    }

    public function compute(): array
    {
        $object_parameters = new ObjectParameter($this->template_parameters);
        return [
            'type'      => "ArrayParameter",
            'key'       => $this->key,
            'label'     => $this->label,
            'items_key' => $this->template_parameters->getDefaultNodeName(),
            'content'   => $object_parameters->compute(),
        ];
    }

    public function getDocumentationUsage(?string $parent = null): string
    {
        $parent = !empty($parent) ? "$parent." : "";
        return "{% for {$this->template_parameters->getDefaultNodeName()} in {$parent}{$this->key} %}";
    }

    public function getDocumentationReferences(): ?TemplatesParametersInterface
    {
        return $this->template_parameters;
    }
}
