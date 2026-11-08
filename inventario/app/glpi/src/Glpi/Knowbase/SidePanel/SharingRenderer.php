<?php

namespace Glpi\Knowbase\SidePanel;

use Glpi\Security\ShareTokenManager;
use KnowbaseItem;
use Override;

final class SharingRenderer implements RendererInterface
{
    // The token is a credential: exposing it needs UPDATE, not canEdit()'s CREATE||PURGE.
    #[Override]
    public function canView(KnowbaseItem $item): bool
    {
        return $item->canManageSharing();
    }

    #[Override]
    public function getTemplate(): string
    {
        return "pages/tools/kb/share_popover.html.twig";
    }

    #[Override]
    public function getParams(KnowbaseItem $item): array
    {
        $id = $item->getID();
        $manager = new ShareTokenManager();
        $token = $manager->getToken(KnowbaseItem::class, $id);

        return [
            'id'           => $id,
            'itemtype'     => KnowbaseItem::class,
            'is_published' => $token !== null && (int) $token['is_active'] === 1,
            'token'        => $token,
            'can_edit'     => $item->canManageSharing(),
        ];
    }
}
