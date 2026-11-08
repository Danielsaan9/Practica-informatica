<?php

namespace Glpi\Knowbase\SidePanel;

use Glpi\Knowbase\CommentsThread;
use KnowbaseItem;
use KnowbaseItem_Comment;
use Override;
use User;

final class CommentsRenderer implements RendererInterface
{
    #[Override]
    public function canView(KnowbaseItem $item): bool
    {
        return $item->canComment();
    }

    #[Override]
    public function getTemplate(): string
    {
        return "pages/tools/kb/sidepanel/comments.html.twig";
    }

    #[Override]
    public function getParams(KnowbaseItem $item): array
    {
        $threads = KnowbaseItem_Comment::getCommentsThreads($item);

        // Load users
        $user_ids = $this->extractRequiredUsersIds($threads);
        $users = [];
        if (count($user_ids) > 0) {
            $users = User::getSeveralFromDBByCrit([
                'id' => $user_ids,
            ]);
            $users = iterator_to_array($users);
        }

        // Transform the user list into an id -> user hash map
        $users = array_combine(
            keys: array_map(fn(User $user) => $user->getID(), $users),
            values: $users,
        );

        return [
            'id'       => $item->getID(),
            'threads'  => $threads,
            'users'    => $users,
        ];
    }

    /**
     * @param CommentsThread[] $threads
     * @return int[]
     */
    private function extractRequiredUsersIds(array $threads): array
    {
        $ids = [];
        foreach ($threads as $thread) {
            foreach ($thread->getComments() as $comment) {
                $ids[] = $comment->fields['users_id'];
            }
        }

        return array_unique($ids);
    }
}
