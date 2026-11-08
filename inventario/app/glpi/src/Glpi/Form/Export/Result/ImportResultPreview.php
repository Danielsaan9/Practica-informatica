<?php

namespace Glpi\Form\Export\Result;

final class ImportResultPreview
{
    /** @var string[] $valid_forms */
    private array $valid_forms = [];

    /** @var string[] $invalid_forms */
    private array $invalid_forms = [];

    /** @var string[] $skipped_forms */
    private array $skipped_forms = [];

    /**
     * Store error messages for a given form ID.
     * Unlike $invalid_forms, this represent errors that aren't recoverable.
     *
     *  @var array<int, array{name: string, errors: array<string>}> $fatal_errors
     **/
    private array $fatal_errors = [];

    public function addValidForm(int $form_id, string $form_name): void
    {
        $this->valid_forms[$form_id] = $form_name;
    }

    /** @return string[] */
    public function getValidForms(): array
    {
        return $this->valid_forms;
    }

    public function addInvalidForm(int $form_id, string $form_name): void
    {
        $this->invalid_forms[$form_id] = $form_name;
    }

    /** @return string[] */
    public function getInvalidForms(): array
    {
        return $this->invalid_forms;
    }

    public function addSkippedForm(int $form_id, string $form_name): void
    {
        $this->skipped_forms[$form_id] = $form_name;
    }

    /** @return string[] */
    public function getSkippedForms(): array
    {
        return $this->skipped_forms;
    }

    public function addFatalErrorForForm(
        int $form_id,
        string $form_name,
        string $error
    ): void {
        if (!isset($this->fatal_errors[$form_id])) {
            $this->fatal_errors[$form_id] = [
                'name' => $form_name,
                'errors' => [],
            ];
        }

        $this->fatal_errors[$form_id]['errors'][] = $error;
    }

    public function hasFatalErrorForForm(int $form_id): bool
    {
        $errors = $this->fatal_errors[$form_id]['errors'] ?? [];
        return count($errors) > 0;
    }

    public function getFormsWithFatalErrors(): array
    {
        return $this->fatal_errors;
    }
}
