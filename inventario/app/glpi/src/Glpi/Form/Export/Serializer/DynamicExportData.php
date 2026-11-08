<?php

namespace Glpi\Form\Export\Serializer;

use Glpi\Form\Export\Specification\DataRequirementSpecification;

final class DynamicExportData
{
    /** @var DynamicExportDataField[] */
    private array $fields = [];

    public function addField(string $field_id, DynamicExportDataField $field): void
    {
        $this->fields[$field_id] = $field;
    }

    public function getFieldData(string $field_id): mixed
    {
        if (!isset($this->fields[$field_id])) {
            return null;
        }

        return $this->fields[$field_id]->getData();
    }

    /** @return DataRequirementSpecification[] */
    public function getRequirements(): array
    {
        $requirements = [];

        foreach ($this->fields as $field) {
            array_push($requirements, ...$field->getRequirements());
        }

        return $requirements;
    }
}
