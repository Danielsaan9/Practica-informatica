<?php

namespace Glpi\Form\Condition;

use JsonSerializable;
use Override;

final class EngineVisibilityOutput implements JsonSerializable
{
    private bool $form_visibility       = true;
    private array $sections_visibility  = [];
    private array $questions_visibility = [];
    private array $comments_visibility  = [];

    #[Override]
    public function jsonSerialize(): array
    {
        return [
            'form_visibility'      => $this->form_visibility,
            'sections_visibility'  => $this->sections_visibility,
            'questions_visibility' => $this->questions_visibility,
            'comments_visibility'  => $this->comments_visibility,
        ];
    }

    public function getNumberOfVisibleSections(): int
    {
        $visible = array_filter(
            $this->sections_visibility,
            fn($is_visible): bool => $is_visible
        );
        return count($visible);
    }

    public function setFormVisibility(bool $is_visible): void
    {
        $this->form_visibility = $is_visible;
    }

    public function setSectionVisibility(int $section_id, bool $is_visible): void
    {
        $this->sections_visibility[$section_id] = $is_visible;
    }

    public function setQuestionVisibility(int $question_id, bool $is_visible): void
    {
        $this->questions_visibility[$question_id] = $is_visible;
    }

    public function setCommentVisibility(int $comment_id, bool $is_visible): void
    {
        $this->comments_visibility[$comment_id] = $is_visible;
    }

    public function isFormVisible(): bool
    {
        return $this->form_visibility;
    }

    public function isSectionVisible(int $section_id): bool
    {
        return $this->sections_visibility[$section_id] ?? false;
    }

    public function isQuestionVisible(int $question_id): bool
    {
        return $this->questions_visibility[$question_id] ?? false;
    }

    public function isCommentVisible(int $comment_id): bool
    {
        return $this->comments_visibility[$comment_id] ?? false;
    }
}
