<?php

namespace Glpi\Form\Tag;

use Glpi\Form\AnswersSet;
use Glpi\Form\Comment;
use Glpi\Form\Form;
use Glpi\Form\FormTranslation;
use Override;

final class CommentTitleTagProvider implements TagProviderInterface, TagWithIdValueInterface
{
    #[Override]
    public function getTagColor(): string
    {
        return "green";
    }

    #[Override]
    public function getTags(Form $form): array
    {
        $tags = [];
        foreach ($form->getFormComments() as $comment) {
            $tags[] = $this->getTitleTagForComment($comment);
        }

        return $tags;
    }

    #[Override]
    public function getTagContentForValue(
        string $value,
        AnswersSet $answers_set
    ): string {
        $id = (int) $value;

        $comment = Comment::getById($id);
        if ($comment === false) {
            return '';
        }
        return FormTranslation::translate($comment, Comment::TRANSLATION_KEY_NAME) ?? $comment->fields['name'];
    }

    #[Override]
    public function getItemtype(): string
    {
        return Comment::class;
    }

    #[Override]
    public function getTagFromRawValue(string $value): ?Tag
    {
        $comment = Comment::getById((int) $value);
        if (!$comment) {
            return null;
        }

        return $this->getTitleTagForComment($comment);
    }

    public function getTitleTagForComment(Comment $comment): Tag
    {
        return new Tag(
            label: sprintf(__('Comment title: %s'), $comment->fields['name']),
            value: $comment->getId(),
            provider: $this,
        );
    }
}
