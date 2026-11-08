<?php

namespace Glpi\Knowbase\SidePanel;

use KnowbaseItem;
use Override;

final class ScheduleVisibilityRenderer implements RendererInterface
{
    #[Override]
    public function canView(KnowbaseItem $item): bool
    {
        return $item->can($item->getID(), UPDATE);
    }

    #[Override]
    public function getTemplate(): string
    {
        return 'pages/tools/kb/modal/schedule_visibility.html.twig';
    }

    #[Override]
    public function getParams(KnowbaseItem $item): array
    {
        return [
            'begin_date' => $item->fields['begin_date'] ?? null,
            'end_date'   => $item->fields['end_date'] ?? null,
        ];
    }
}
