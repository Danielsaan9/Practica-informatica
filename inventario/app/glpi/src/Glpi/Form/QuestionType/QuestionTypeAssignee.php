<?php

namespace Glpi\Form\QuestionType;

use Exception;
use Glpi\Form\Question;
use Group;
use Override;
use Session;
use Supplier;
use User;

final class QuestionTypeAssignee extends AbstractQuestionTypeActors
{
    #[Override]
    public function getName(): string
    {
        return _n('Assignee', 'Assignees', Session::getPluralNumber());
    }

    #[Override]
    public function getIcon(): string
    {
        return 'ti ti-user-check';
    }

    #[Override]
    public function getWeight(): int
    {
        return 30;
    }

    #[Override]
    public function getAllowedActorTypes(): array
    {
        return [User::class, Group::class, Supplier::class];
    }

    #[Override]
    public function getRightForUsers(): string
    {
        return 'own_ticket';
    }

    #[Override]
    public function getGroupConditions(): array
    {
        return ['is_assign' => 1];
    }

    #[Override]
    public function prepareEndUserAnswer(Question $question, mixed $answer): mixed
    {
        $actors = parent::prepareEndUserAnswer($question, $answer);
        foreach ($actors as $actor) {
            // Whether a User actor can actually be assigned depends on the
            // ITIL object's entity, which is not known yet at this stage
            // (it can depend on the requester, on another answer, ...).
            // This is validated later, once that entity is resolved, in
            // ITILActorField::isActorAllowed().
            if ($actor['itemtype'] === Group::class) {
                // Check if the group can be assigned
                if (Group::getById($actor['items_id'])->fields['is_assign'] !== 1) {
                    throw new Exception('Invalid actor: must be able to be assigned');
                }
            }
        }

        return $actors;
    }
}
