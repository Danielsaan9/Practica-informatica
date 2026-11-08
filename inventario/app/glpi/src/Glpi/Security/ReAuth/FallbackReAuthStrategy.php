<?php

declare(strict_types=1);

namespace Glpi\Security\ReAuth;

use Override;
use Symfony\Component\HttpFoundation\Request;

/**
 * Fallback re-authentication strategy.
 *
 * Always available and always succeeds: it only displays a confirmation
 * message in the prompt. It exists so that a user who has no other strategy
 * available (e.g. no local password and no TOTP) is never left without a way
 * to pass the re-authentication step.
 *
 * As it provides no real identity check, it has the lowest priority and is
 * only selected when no stronger strategy is available.
 */
final class FallbackReAuthStrategy extends InPlaceReAuthStrategy
{
    #[Override]
    public function verify(int $users_id, Request $request): bool
    {
        return true;
    }

    #[Override]
    public function isAvailable(int $users_id, int $entities_id = 0): bool
    {
        return true;
    }

    #[Override]
    public function getLabel(): string
    {
        return __('Confirmation');
    }

    #[Override]
    public function getPromptTemplate(): string
    {
        return 'pages/reauth/fallback_form.html.twig';
    }

    #[Override]
    public function getPriority(): int
    {
        return 0;
    }
}
