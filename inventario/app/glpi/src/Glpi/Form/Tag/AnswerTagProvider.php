<?php

namespace Glpi\Form\Tag;

use Glpi\Form\Answer;
use Glpi\Form\AnswersSet;
use Glpi\Form\Form;
use Glpi\Form\Question;
use Override;

final class AnswerTagProvider implements TagProviderInterface, TagWithIdValueInterface
{
    #[Override]
    public function getTagColor(): string
    {
        return "teal";
    }

    #[Override]
    public function getTags(Form $form): array
    {
        $tags = [];
        foreach ($form->getQuestions() as $question) {
            $tags[] = $this->getTagForQuestion($question);
        }

        return $tags;
    }

    #[Override]
    public function getTagContentForValue(
        string $value,
        AnswersSet $answers_set
    ): string {
        $id = (int) $value;

        $answers = array_filter(
            $answers_set->getAnswers(),
            fn(Answer $answer) => $answer->getQuestionId() === $id
        );

        if (count($answers) !== 1) {
            return "";
        }

        $answer = array_pop($answers);
        return $answer->getFormattedAnswer() ?? '';
    }

    #[Override]
    public function getItemtype(): string
    {
        return Question::class;
    }

    #[Override]
    public function getTagFromRawValue(string $value): ?Tag
    {
        $question = Question::getById((int) $value);
        if (!$question) {
            return null;
        }

        return $this->getTagForQuestion($question);
    }

    public function getTagForQuestion(Question $question): Tag
    {
        return new Tag(
            label: sprintf(__('Answer: %s'), $question->fields['name']),
            value: $question->getId(),
            provider: $this,
        );
    }
}
