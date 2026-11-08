<?php

declare(strict_types=1);

namespace Glpi\Security\ReAuth;

use Glpi\Security\TOTPManager;
use Symfony\Component\HttpFoundation\Request;

final class TOTPReAuthStrategy extends InPlaceReAuthStrategy
{
    private TOTPManager $totp_manager;

    public function __construct()
    {
        $this->totp_manager = new TOTPManager();
    }

    #[\Override]
    public function verify(int $users_id, Request $request): bool
    {
        $user_input = implode('', $request->request->all('totp_code'));

        return $this->totp_manager->verifyCodeForUser($user_input, $users_id);
    }

    #[\Override]
    public function isAvailable(int $users_id, int $entities_id = 0): bool
    {
        return $this->totp_manager->is2FAEnabled($users_id);
    }

    #[\Override]
    public function getLabel(): string
    {
        return __('Two-factor authentication');
    }

    #[\Override]
    public function getPromptTemplate(): string
    {
        return 'pages/reauth/totp_form.html.twig';
    }

    #[\Override]
    public function getPriority(): int
    {
        return 100;
    }
}
