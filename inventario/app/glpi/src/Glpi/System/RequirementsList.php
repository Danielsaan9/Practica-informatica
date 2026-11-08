<?php

namespace Glpi\System;

use ArrayIterator;
use Glpi\System\Requirement\RequirementInterface;
use IteratorAggregate;
use Traversable;

/**
 * @since 9.5.0
 * @implements IteratorAggregate<RequirementInterface>
 */
class RequirementsList implements IteratorAggregate
{
    /**
     * Requirements.
     *
     * @var RequirementInterface[]
     */
    private array $requirements;

    /**
     * @param RequirementInterface[] $requirements
     */
    public function __construct(array $requirements = [])
    {
        $this->requirements = $requirements;
    }

    public function add(RequirementInterface $requirement): void
    {
        $this->requirements[] = $requirement;
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->requirements);
    }

    /**
     * Indicates if a mandatory requirement is missing.
     *
     * @return bool
     */
    public function hasMissingMandatoryRequirements()
    {
        foreach ($this->requirements as $requirement) {
            if (!$requirement->isOptional() && !$requirement->isOutOfContext() && $requirement->isMissing()) {
                return true;
            }
        }
        return false;
    }

    /**
     * Indicates if an optional requirement is missing.
     *
     * @return bool
     */
    public function hasMissingOptionalRequirements()
    {
        foreach ($this->requirements as $requirement) {
            if ($requirement->isOptional() && !$requirement->isOutOfContext() && $requirement->isMissing()) {
                return true;
            }
        }
        return false;
    }

    /**
     * Get messages returned by the failed mandatory requirements.
     *
     * @return array
     */
    public function getErrorMessages(): array
    {
        $messages = [];
        foreach ($this->requirements as $requirement) {
            if ($requirement->isValidated() || $requirement->isOptional()) {
                continue;
            }
            array_push($messages, ...$requirement->getValidationMessages());
        }

        return $messages;
    }
}
