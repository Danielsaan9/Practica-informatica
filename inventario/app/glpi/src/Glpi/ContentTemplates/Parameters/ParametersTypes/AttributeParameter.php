<?php

namespace Glpi\ContentTemplates\Parameters\ParametersTypes;

use Glpi\ContentTemplates\Parameters\TemplatesParametersInterface;

/**
 * AttributeParameter represent a simple parameter value accessed by a key.
 * This can be a simple value from the database (e.g the title of a ticket) or a
 * computed value (e.g. the link to a ticket).
 *
 * @since 10.0.0
 */
class AttributeParameter extends AbstractParameterType
{
    /**
     * Suggested twig filter to use when displaying the value of this parameter
     * This may be a 'raw" filter when the value is raw html, a 'date' filter
     * when dealing with timestamp so the user know how to reformat the date as
     * needed, ...
     *
     */
    protected string $filter;

    /**
     * @param string $key    Key to access this value
     * @param string $label  Label to display in the autocompletion widget
     * @param string $filter Recommanded twig filter to apply on this value
     */
    public function __construct(string $key, string $label, string $filter = "")
    {
        parent::__construct($key, $label);
        $this->filter = $filter;
    }

    public function compute(): array
    {
        return [
            'type'   => "AttributeParameter",
            'key'    => $this->key,
            'label'  => $this->label,
            'filter' => $this->filter,
        ];
    }

    public function getDocumentationUsage(?string $parent = null): string
    {
        $parent = !empty($parent) ? "$parent." : "";
        $filter = !empty($this->filter) ? "| {$this->filter}" : "";
        return "{{ {$parent}{$this->key} $filter }}";
    }

    public function getDocumentationReferences(): ?TemplatesParametersInterface
    {
        return null;
    }
}
