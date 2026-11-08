<?php

namespace Glpi\Form\Condition;

interface StrategyInterface
{
    public function getLabel(): string;

    public function getIcon(): string;

    public function showEditor(): bool;
}
