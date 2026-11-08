<?php

namespace Glpi\Form\Condition;

use Glpi\Form\Destination\FormDestination;
use JsonSerializable;
use Override;

final class EngineCreationOutput implements JsonSerializable
{
    private array $must_be_created = [];

    #[Override]
    public function jsonSerialize(): array
    {
        return [
            'must_be_created' => $this->must_be_created,
        ];
    }

    public function addItemThatMustBeCreated(FormDestination $destination): void
    {
        $this->must_be_created[] = $destination->getId();
    }

    public function itemMustBeCreated(FormDestination $destination): bool
    {
        return in_array(
            $destination->getId(),
            $this->must_be_created,
            true
        );
    }
}
