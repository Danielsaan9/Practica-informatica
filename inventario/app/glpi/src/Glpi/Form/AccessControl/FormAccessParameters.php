<?php

namespace Glpi\Form\AccessControl;

use Glpi\Session\SessionInfo;

final readonly class FormAccessParameters
{
    public function __construct(
        private ?SessionInfo $session_info = null,
        private array $url_parameters = [],
        private bool $bypass_restriction = false,
    ) {}

    public function isAuthenticated(): bool
    {
        return $this->session_info !== null;
    }

    public function getSessionInfo(): ?SessionInfo
    {
        return $this->session_info;
    }

    public function getUrlParameters(): array
    {
        return $this->url_parameters;
    }

    public function isBypassingRestrictions(): bool
    {
        return $this->bypass_restriction;
    }
}
