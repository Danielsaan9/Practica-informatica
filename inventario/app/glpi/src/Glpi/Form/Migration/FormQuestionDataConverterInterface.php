<?php

namespace Glpi\Form\Migration;

use Glpi\Form\QuestionType\QuestionTypeInterface;

interface FormQuestionDataConverterInterface
{
    /**
     * Convert default value
     *
     * @param array $rawData
     * @return mixed
     */
    public function convertDefaultValue(array $rawData): mixed;

    /**
     * Convert extra data
     *
     * @param array $rawData
     * @return mixed
     */
    public function convertExtraData(array $rawData): mixed;

    /**
     * @return class-string<QuestionTypeInterface>
     */
    public function getTargetQuestionType(array $rawData): string;

    /**
     * Allow the converter to run some arbitrary code before we begin converting
     * values.
     *
     * For example, it might be used to create some required database items.
     */
    public function beforeConversion(array $rawData): void;
}
