<?php

namespace Glpi\Form\Destination;

interface ConfigFieldWithStrategiesInterface
{
    /**
     * Get strategies input name
     */
    public static function getStrategiesInputName(): string;

    /**
     * Get actual strategies
     *
     * @return array
     */
    public function getStrategies(): array;
}
