<?php

namespace Glpi\Form\Destination\CommonITILField;

use Glpi\Form\Form;
use Glpi\Form\QuestionType\QuestionTypeEmail;
use Glpi\Form\QuestionType\QuestionTypeObserver;
use Group;
use Override;
use Session;
use User;

final class ObserverField extends ITILActorField
{
    #[Override]
    public function getAllowedQuestionType(): array
    {
        return [new QuestionTypeObserver(), new QuestionTypeEmail()];
    }

    #[Override]
    public function getAllowedQuestionItemTypes(): array
    {
        return [User::class, Group::class];
    }

    #[Override]
    public function getActorType(): string
    {
        return 'observer';
    }

    #[Override]
    public function getLabel(): string
    {
        return _n('Observer', 'Observers', Session::getPluralNumber());
    }

    #[Override]
    public function getWeight(): int
    {
        return 110;
    }

    #[Override]
    public function getConfigClass(): string
    {
        return ObserverFieldConfig::class;
    }

    #[Override]
    public function getDefaultConfig(Form $form): ObserverFieldConfig
    {
        return new ObserverFieldConfig(
            [ITILActorFieldStrategy::FROM_TEMPLATE],
        );
    }
}
