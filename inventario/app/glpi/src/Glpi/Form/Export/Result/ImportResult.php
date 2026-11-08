<?php

namespace Glpi\Form\Export\Result;

use Glpi\Form\Form;

final class ImportResult
{
    /** @var Form[] $imported_forms */
    private array $imported_forms = [];

    /** @var array<string, ImportError> $failed_forms */
    private array $failed_forms = [];

    public function addImportedForm(Form $form): void
    {
        $this->imported_forms[] = $form;
    }

    /** @return Form[] */
    public function getImportedForms(): array
    {
        return $this->imported_forms;
    }

    public function addFailedFormImport(
        string $form_name,
        ImportError $error,
    ): void {
        $this->failed_forms[$form_name] = $error;
    }

    /** @return array<string, ImportError> */
    public function getFailedFormImports(): array
    {
        return $this->failed_forms;
    }
}
