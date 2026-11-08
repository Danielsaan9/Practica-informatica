<?php

namespace Glpi\Knowbase\History;

interface HistoryEventInterface
{
    public function getLabel(): string;

    public function getDescription(): string;

    public function getDate(): ?string;

    public function getAuthor(): int;
}
