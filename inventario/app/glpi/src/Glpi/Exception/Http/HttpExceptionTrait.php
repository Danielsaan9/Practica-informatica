<?php

namespace Glpi\Exception\Http;

trait HttpExceptionTrait
{
    private ?string $message_to_display = null;

    private ?string $link_text = null;

    private ?string $link_url = null;

    /**
     * Get the message to display.
     */
    public function getMessageToDisplay(): ?string
    {
        return $this->message_to_display;
    }

    /**
     * Define the message to display.
     */
    public function setMessageToDisplay(?string $message): void
    {
        $this->message_to_display = $message;
    }

    /**
     * Get the specific link text.
     */
    public function getLinkText(): ?string
    {
        return $this->link_text;
    }

    /**
     * Define the specific link text.
     */
    public function setLinkText(?string $text): void
    {
        $this->link_text = $text;
    }

    /**
     * Get the specific link URL.
     */
    public function getLinkUrl(): ?string
    {
        return $this->link_url;
    }

    /**
     * Define the specific link URL.
     */
    public function setLinkUrl(?string $url): void
    {
        $this->link_url = $url;
    }
}
