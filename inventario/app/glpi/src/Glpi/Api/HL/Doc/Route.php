<?php

namespace Glpi\Api\HL\Doc;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class Route
{
    /**
     * @param string $description
     * @param string[] $methods
     * @param array<Parameter|ParameterReference> $parameters
     * @param Response[] $responses
     */
    public function __construct(
        protected string $description = '',
        protected array $methods = [],
        protected array $parameters = [],
        protected array $responses = []
    ) {}

    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Array of methods that this documentation applies to.
     * If empty, it will be applied to every method defined in the Route itself.
     * @return string[]
     */
    public function getMethods(): array
    {
        return $this->methods;
    }

    /**
     * @return array<Parameter|ParameterReference>
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * @return Response[]
     */
    public function getResponses(): array
    {
        return $this->responses;
    }
}
