<?php

namespace Glpi\Form\Export\Result;

use Glpi\Form\Export\Specification\DataRequirementSpecification;

final class ImportResultIssues
{
    /** @var array<int, DataRequirementSpecification[]> $valid_forms */
    private array $issues = [];

    /**
     * @param int $form_id
     * @param DataRequirementSpecification[] $issues
     */
    public function addIssuesForForm(int $form_id, array $issues): void
    {
        $this->issues[$form_id] = $issues;
    }

    /** @return array<int, DataRequirementSpecification[]> */
    public function getIssues(): array
    {
        return $this->issues;
    }
}
