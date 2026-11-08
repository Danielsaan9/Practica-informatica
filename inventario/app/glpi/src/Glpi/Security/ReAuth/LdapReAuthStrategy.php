<?php

declare(strict_types=1);

namespace Glpi\Security\ReAuth;

use Auth;
use Override;
use Symfony\Component\HttpFoundation\Request;
use User;

/**
 * LDAP re-authentication strategy.
 *
 * Verifies the user identity by performing an LDAP bind with the password
 * provided in the prompt, reusing the same directory configuration as the
 * regular login flow.
 *
 * It fails closed: if the directory is unreachable, the verification fails
 * and no bypass is granted, so the sensitive action stays protected.
 */
final class LdapReAuthStrategy extends InPlaceReAuthStrategy
{
    #[Override]
    public function verify(int $users_id, Request $request): bool
    {
        $ldap_password = (string) $request->request->get('user_input', '');

        // Guard against empty password and null-byte injection: some directories accept an
        // unauthenticated (anonymous) bind on an empty password, which would turn this check
        // into an authentication bypass.
        if ($ldap_password === '' || str_contains($ldap_password, "\0")) {
            return false;
        }

        $user = new User();
        if (!$user->getFromDB($users_id)) {
            return false;
        }

        $ldap_method = Auth::getMethodsByID(Auth::LDAP, (int) $user->fields['auths_id']);
        if ($ldap_method === []) {
            return false;
        }

        // connection_ldap() returns the LDAP entry on success, or false on a wrong password
        // or an unreachable directory. Both cases fail closed here.
        $result = (new Auth())->connection_ldap($ldap_method, $user->fields['name'], $ldap_password);

        return $result !== false;
    }

    #[Override]
    public function isAvailable(int $users_id, int $entities_id = 0): bool
    {
        $user = new User();
        if (!$user->getFromDB($users_id)) {
            return false;
        }

        $session_authtype = $_SESSION['glpiauthtype'] ?? Auth::NOT_YET_AUTHENTIFIED;
        // An SSO account backed by a directory still knows its directory password
        $is_sso_backed_by_ldap = $session_authtype === Auth::EXTERNAL
            && $user->fields['authtype'] === Auth::LDAP;

        if ($session_authtype !== Auth::LDAP && !$is_sso_backed_by_ldap) {
            return false;
        }

        // A missing server or an empty host can never be verified: let a weaker strategy
        // take over. An unreachable server, on the other hand, fails closed in verify().
        $method = Auth::getMethodsByID(Auth::LDAP, (int) $user->fields['auths_id']);
        return !empty($method['host']);
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
