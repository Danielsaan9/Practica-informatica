<?php

namespace Glpi\Form\QuestionType;

interface CustomMandatoryMessageInterface
{
    public function getCustomMandatoryErrorMessage(): string;
}
