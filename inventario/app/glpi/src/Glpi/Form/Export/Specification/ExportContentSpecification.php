<?php

namespace Glpi\Form\Export\Specification;

final class ExportContentSpecification
{
    public int $version;

    /** @var FormContentSpecification[] */
    public array $forms = [];

    public function addForm(FormContentSpecification $spec): void
    {
        $this->forms[] = $spec;
    }
}
