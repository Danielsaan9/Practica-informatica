<?php

namespace Glpi\System\Requirement;

/**
 * @since 9.5.0
 */
abstract class AbstractRequirement implements RequirementInterface
{
    /**
     * Flag that indicates if requirement check has already been done.
     *
     */
    private bool $has_been_checked = false;

    /**
     * Flag that indicates if requirement is considered as optional.
     *
     */
    protected ?bool $optional = null;

    /**
     * Flag that indicates if requirement is recommended for security reasons.
     *
     */
    protected ?bool $recommended_for_security;

    /**
     * Flag that indicates if requirement is considered as out of context.
     *
     */
    protected ?bool $out_of_context = null;

    /**
     * Requirement title.
     *
     */
    protected ?string $title = null;

    /**
     * Requirement description.
     *
     */
    protected ?string $description = null;

    /**
     * Flag that indicates if requirement is validated on system.
     *
     */
    protected bool $validated;

    /**
     * Requirement validation message.
     *
     * @var string[]
     */
    protected array $validation_messages = [];

    public function __construct(
        ?string $title,
        ?string $description = null,
        ?bool $optional = false,
        ?bool $recommended_for_security = false,
        ?bool $out_of_context = false
    ) {
        $this->title = $title;
        $this->description = $description;
        $this->optional = $optional;
        $this->recommended_for_security = $recommended_for_security;
        $this->out_of_context = $out_of_context;
    }

    /**
     * Check requirement.
     *
     * This method will be called once before access to any RequirementInterface method
     * and should be used to compute  $validated and $validation_messages properties.
     *
     * @return void
     */
    abstract protected function check();

    /**
     * Run requirement check once.
     *
     * @return void
     */
    private function doCheck()
    {
        if (!$this->has_been_checked) {
            $this->check();
            $this->has_been_checked = true;
        }
    }

    public function getTitle(): string
    {
        if ($this->title !== null) {
            // No need to run checks if variable is defined by constructor.
            return $this->title;
        }

        $this->doCheck();

        return $this->title ?? '';
    }

    public function getDescription(): ?string
    {
        if ($this->description !== null) {
            // No need to run checks if variable is defined by constructor.
            return $this->description;
        }

        $this->doCheck();

        return $this->description;
    }

    public function getValidationMessages(): array
    {
        $this->doCheck();

        return $this->validation_messages;
    }

    public function isMissing(): bool
    {
        $this->doCheck();

        return true !== $this->validated;
    }

    public function isOptional(): bool
    {
        if ($this->optional !== null) {
            // No need to run checks if variable is defined by constructor.
            return $this->optional;
        }

        $this->doCheck();

        return $this->optional ?? false;
    }

    public function isRecommendedForSecurity(): bool
    {
        if ($this->recommended_for_security !== null) {
            // No need to run checks if variable is defined by constructor.
            return $this->recommended_for_security;
        }

        $this->doCheck();

        return $this->recommended_for_security ?? false;
    }

    public function isOutOfContext(): bool
    {
        if ($this->out_of_context !== null) {
            // No need to run checks if variable is defined by constructor.
            return $this->out_of_context;
        }

        $this->doCheck();

        return $this->out_of_context ?? false;
    }

    public function isValidated(): bool
    {
        $this->doCheck();

        return true === $this->validated;
    }
}
