<?php

namespace Glpi\Knowbase\SidePanel;

use KnowbaseItem;

interface RendererInterface
{
    public function canView(KnowbaseItem $item): bool;

    public function getTemplate(): string;

    /** @return array<string, mixed> */
    public function getParams(KnowbaseItem $item): array;
}
