<?php

namespace Glpi\Form\Destination\CommonITILField;

use Glpi\Form\Form;
use Glpi\Form\QuestionType\QuestionTypeAssignee;
use Glpi\Form\QuestionType\QuestionTypeEmail;
use Group;
use Override;
use Session;
use Supplier;
use User;

final class AssigneeField extends ITILActorField
{
    #[Override]
    public function getAllowedQuestionType(): array
    {
        return [new QuestionTypeAssignee(), new QuestionTypeEmail()];
    }

    #[Override]
    public function getAllowedQuestionItemTypes(): array
    {
        return [User::class, Group::class, Supplier::class];
    }

    #[Override]
    public function getActorType(): string
    {
        return 'assign';
    }

    #[Override]
    public function getLabel(): string
    {
        return _n('Assignee', 'Assignees', Session::getPluralNumber());
    }

    #[Override]
    public function getWeight(): int
    {
        return 120;
    }

    #[Override]
    public function getConfigClass(): string
    {
        return AssigneeFieldConfig::class;
    }

    #[Override]
    public function getDefaultConfig(Form $form): AssigneeFieldConfig
    {
        return new AssigneeFieldConfig(
            [ITILActorFieldStrategy::FROM_TEMPLATE],
        );
    }
}
