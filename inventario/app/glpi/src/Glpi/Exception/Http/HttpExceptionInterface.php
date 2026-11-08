<?php

namespace Glpi\Exception\Http;

interface HttpExceptionInterface extends \Symfony\Component\HttpKernel\Exception\HttpExceptionInterface
{
    /**
     * Get the message to display.
     */
    public function getMessageToDisplay(): ?string;

    /**
     * Get the specific link text.
     */
    public function getLinkText(): ?string;

    /**
     * Get the specific link URL.
     */
    public function getLinkUrl(): ?string;
}
