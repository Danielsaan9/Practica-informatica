<?php

declare(strict_types=1);

namespace Glpi\Security\ReAuth;

use Auth;
use Override;
use Symfony\Component\HttpFoundation\Request;
use User;

final class PasswordReAuthStrategy extends InPlaceReAuthStrategy
{
    #[Override]
    public function verify(int $users_id, Request $request): bool
    {
        $user = new User();
        if (!$user->getFromDB($users_id)) {
            return false;
        }

        $user_input = (string) $request->request->get('user_input', '');

        return Auth::checkPassword($user_input, $user->fields['password']);
    }

    #[Override]
    public function isAvailable(int $users_id, int $entities_id = 0): bool
    {
        $user = new User();
        if (!$user->getFromDB($users_id)) {
            return false;
        }

        return ($_SESSION['glpiauthtype'] ?? Auth::NOT_YET_AUTHENTIFIED) === Auth::DB_GLPI
            && !empty($user->fields['password']);
    }

    #[Override]
    public function getLabel(): string
    {
        return __('Password');
    }

    #[Override]
    public function getPromptTemplate(): string
    {
        return 'pages/reauth/password_form.html.twig';
    }

    #[Override]
    public function getPriority(): int
    {
        return 50;
    }
}
