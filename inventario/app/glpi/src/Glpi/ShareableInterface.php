<?php

namespace Glpi;

interface ShareableInterface
{
    /**
     * Get the URL for viewing this item (used for authenticated user redirect).
     */
    public function getItemUrl(): string;

    /**
     * Check if the current user can manage sharing for this item.
     */
    public function canManageSharing(): bool;

    /**
     * Whether an item of this type may hold several share tokens at once.
     */
    public function allowsMultipleShareTokens(): bool;

    /**
     * Get the Twig template path for the anonymous public view.
     */
    public function getShareableViewTemplate(): string;

    /**
     * Get the template parameters for the anonymous public view.
     *
     * @return array<string, mixed>
     */
    public function getShareableViewParams(): array;
}
