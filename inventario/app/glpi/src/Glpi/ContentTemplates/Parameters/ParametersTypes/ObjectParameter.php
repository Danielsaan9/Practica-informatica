<?php

namespace Glpi\ContentTemplates\Parameters\ParametersTypes;

use Glpi\ContentTemplates\Parameters\TemplatesParametersInterface;
use Glpi\ContentTemplates\TemplateManager;

/**
 * ObjectParameter represent a whole object to use as a parameter.
 * For exemple, this entity of a ticket or its category.
 *
 * @since 10.0.0
 */
class ObjectParameter extends AbstractParameterType
{
    /**
     * Parameters availables in the item that will be linked.
     *
     */
    protected TemplatesParametersInterface $template_parameters;

    /**
     * @param TemplatesParametersInterface $template_parameters Parameters to add
     * @param null|string                  $key                 Key to access this value
     */
    public function __construct(TemplatesParametersInterface $template_parameters, ?string $key = null)
    {
        parent::__construct(
            $key ?? $template_parameters->getDefaultNodeName(),
            $template_parameters->getObjectLabel()
        );
        $this->template_parameters = $template_parameters;
    }

    public function compute(): array
    {
        $sub_parameters = $this->template_parameters->getAvailableParameters();
        $properties =  TemplateManager::computeParameters($sub_parameters);

        return [
            'type'       => "ObjectParameter",
            'key'        => $this->key,
            'label'      => $this->label,
            'properties' => $properties,
        ];
    }

    public function getDocumentationUsage(?string $parent = null): string
    {
        $parent = !empty($parent) ? "$parent." : "";
        return "{{ {$parent}{$this->key}.XXX }}";
    }

    public function getDocumentationReferences(): ?TemplatesParametersInterface
    {
        return $this->template_parameters;
    }
}
