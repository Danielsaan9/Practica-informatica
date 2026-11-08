<?php

namespace Glpi\Form\QuestionType;

use Exception;
use Glpi\Form\Question;
use Group;
use Override;
use Session;
use User;

final class QuestionTypeRequester extends AbstractQuestionTypeActors
{
    #[Override]
    public function getName(): string
    {
        return _n('Requester', 'Requesters', Session::getPluralNumber());
    }

    #[Override]
    public function getIcon(): string
    {
        return 'ti ti-user-question';
    }

    #[Override]
    public function getWeight(): int
    {
        return 10;
    }

    #[Override]
    public function getAllowedActorTypes(): array
    {
        return [User::class, Group::class];
    }

    #[Override]
    public function getGroupConditions(): array
    {
        return ['is_requester' => 1];
    }

    #[Override]
    public function prepareEndUserAnswer(Question $question, mixed $answer): mixed
    {
        $actors = parent::prepareEndUserAnswer($question, $answer);
        foreach ($actors as $actor) {
            if ($actor['itemtype'] === Group::class) {
                // Check if the group can be assigned
                if (Group::getById($actor['items_id'])->fields['is_requester'] !== 1) {
                    throw new Exception('Invalid actor: must be a requester');
                }
            }
        }

        return $actors;
    }
}
