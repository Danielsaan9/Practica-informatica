<?php

namespace Glpi\Form\Export\Serializer;

use Glpi\Form\Export\Specification\DataRequirementSpecification;

final readonly class DynamicExportDataField
{
    private mixed $data;

    /** @var DataRequirementSpecification[] */
    private array $requirements;

    public function __construct(
        mixed $data,
        array $requirements
    ) {
        $this->data = $data;
        $this->requirements = $requirements;
    }

    public function getData(): mixed
    {
        return $this->data;
    }


    /** @return DataRequirementSpecification[] */
    public function getRequirements(): array
    {
        return $this->requirements;
    }
}
