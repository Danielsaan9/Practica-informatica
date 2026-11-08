<?php

namespace Glpi\Form;

/**
 * Class for handling form validation results
 */
final class ValidationResult
{
    /**
     * @var bool Indicates if the validation is successful
     */
    private bool $valid;

    /**
     * @var array List of errors found during validation
     */
    private array $errors;

    /**
     * Constructor
     *
     * @param bool  $valid  Indicates if the validation is successful
     * @param array $errors List of errors found during validation
     */
    public function __construct(bool $valid = true, array $errors = [])
    {
        $this->valid = $valid;
        $this->errors = $errors;
    }

    /**
     * Check if the validation is successful
     *
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->valid;
    }

    /**
     * Get the list of errors found during validation
     *
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Add an error to the list
     *
     * @param Question $question The question associated with the error
     * @param string   $message  The error message
     * @return void
     */
    public function addError(Question $question, string $message): void
    {
        $error = [
            'question_id'   => $question->getID(),
            'question_name' => $question->getName(),
            'message'       => $message,
        ];

        $this->valid = false;
        $this->addFormattedError($error);
    }

    /**
     * Add an error to the list (already formatted)
     */
    public function addFormattedError(array $formatted_error): void
    {
        $this->valid = false;
        $this->errors[] = $formatted_error;
    }
}
