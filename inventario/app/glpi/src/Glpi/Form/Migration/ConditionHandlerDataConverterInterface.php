<?php

namespace Glpi\Form\Migration;

interface ConditionHandlerDataConverterInterface
{
    /**
     * Convert conditions value
     *
     * @param string $value
     * @return mixed
     */
    public function convertConditionValue(string $value): mixed;
}
