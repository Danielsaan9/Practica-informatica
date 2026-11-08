<?php

namespace Glpi\Knowbase;

use KnowbaseItem_Comment;
use RuntimeException;

final class CommentsThread
{
    public function __construct(
        /** @var KnowbaseItem_Comment[] $comments */
        private array $comments = [],
    ) {}

    public function addComment(KnowbaseItem_Comment $comment): void
    {
        $this->comments[] = $comment;
    }

    /** @return KnowbaseItem_Comment[] */
    public function getComments(): array
    {
        return $this->comments;
    }

    public function getRootComment(): KnowbaseItem_Comment
    {
        $key = array_key_first($this->comments);
        if ($key === null) {
            throw new RuntimeException("Thread is empty");
        }

        return $this->comments[$key];
    }
}
