<?php

namespace Glpi\Form\Tag;

use Glpi\Form\Answer;
use Glpi\Form\AnswersSet;
use Glpi\Form\Form;
use Glpi\Form\FormTranslation;
use Glpi\Form\Question;
use Override;

final class QuestionTagProvider implements TagProviderInterface, TagWithIdValueInterface
{
    #[Override]
    public function getTagColor(): string
    {
        return "orange";
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

        $question = Question::getById($id);
        if (!$question) {
            return '';
        }

        // Questions without answer were not displayed to the requester (hidden
        // by a visibility condition), so they must not be displayed either.
        $answers = array_filter(
            $answers_set->getAnswers(),
            fn(Answer $answer) => $answer->getQuestionId() === $id
        );
        if ($answers === []) {
            return '';
        }

        return FormTranslation::translate($question, Question::TRANSLATION_KEY_NAME) ?? $question->fields['name'];
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
            label: sprintf(__('Question: %s'), $question->fields['name']),
            value: $question->getId(),
            provider: $this,
        );
    }
}
