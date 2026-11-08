<?php

namespace Glpi\Form;

use Glpi\Form\QuestionType\QuestionTypeInterface;
use Glpi\Form\QuestionType\RawAnswerIsHtmlInterface;
use InvalidArgumentException;
use JsonSerializable;

final readonly class Answer implements JsonSerializable
{
    private int $question_id;
    private string $question_label;
    private string $raw_question_type;
    private mixed $raw_answer;

    public function __construct(Question $question, mixed $raw_answer)
    {
        $this->question_id       = $question->fields['id'];
        $this->question_label    = $question->fields['name'];
        $this->raw_question_type = $question->fields['type'];
        $this->raw_answer        = $raw_answer;
    }

    public static function fromDecodedJsonData(array $data): self
    {
        if (
            !isset(
                $data['question_id'],
                $data['question_label'],
                $data['raw_question_type'],
                $data['raw_answer'],
            )
        ) {
            throw new InvalidArgumentException('Invalid JSON data');
        }

        $question = new Question();
        $question->fields['id']   = $data['question_id'];
        $question->fields['name'] = $data['question_label'];
        $question->fields['type'] = $data['raw_question_type'];

        return new self($question, $data['raw_answer']);
    }

    public function getQuestionId(): int
    {
        return $this->question_id;
    }

    public function getRawAnswer(): mixed
    {
        return $this->raw_answer;
    }

    public function getFormattedAnswer(): ?string
    {
        $type = $this->getType();
        if ($type === null) {
            return null;
        }

        $question = Question::getById($this->getQuestionId());
        if ($question === false) {
            return null;
        }

        $formatted = $type->formatRawAnswer($this->getRawAnswer(), $question);

        if (!$type instanceof RawAnswerIsHtmlInterface) {
            $formatted = \htmlescape($formatted);
        }

        return $formatted;
    }

    public function getQuestionLabel(): string
    {
        return $this->question_label;
    }

    public function getRawType(): string
    {
        return $this->raw_question_type;
    }

    public function getType(): ?QuestionTypeInterface
    {
        $type = $this->getRawType();
        if (!is_a($type, QuestionTypeInterface::class, true)) {
            return null;
        }

        return new $type();
    }

    public function jsonSerialize(): array
    {
        return [
            'question_id'       => $this->getQuestionId(),
            'question_label'    => $this->getQuestionLabel(),
            'raw_question_type' => $this->getRawType(),
            'raw_answer'        => $this->getRawAnswer(),
        ];
    }
}
