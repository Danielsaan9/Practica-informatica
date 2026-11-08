<?php

namespace Glpi\Form\AccessControl\ControlType;

use Glpi\DBAL\JsonFieldInterface;
use Glpi\DBAL\PrepareForCloneInterface;
use Override;
use Toolbox;

final class DirectAccessConfig implements JsonFieldInterface, PrepareForCloneInterface
{
    public function __construct(
        private string $token = "",
        private bool $allow_unauthenticated = false,
    ) {
        if (empty($this->token)) {
            $this->token = $this->generateToken();
        }
    }

    #[Override]
    public static function jsonDeserialize(array $data): self
    {
        return new self(
            token: $data['token'] ?? "",
            allow_unauthenticated: $data['allow_unauthenticated'] ?? false,
        );
    }

    #[Override]
    public function jsonSerialize(): array
    {
        return [
            'token' => $this->token,
            'allow_unauthenticated' => $this->allow_unauthenticated,
        ];
    }

    #[Override]
    public function prepareInputForClone(array $data): array
    {
        // Make sure the token is always unique
        $data['token'] = $this->generateToken();
        return $data;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function allowUnauthenticated(): bool
    {
        return $this->allow_unauthenticated;
    }

    private function generateToken(): string
    {
        return Toolbox::getRandomString(40);
    }
}
