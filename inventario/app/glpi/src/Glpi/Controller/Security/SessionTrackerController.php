<?php

namespace Glpi\Controller\Security;

use Config;
use Glpi\Controller\AbstractController;
use Glpi\Exception\Http\AccessDeniedHttpException;
use Glpi\Http\Firewall;
use Glpi\OAuth\AccessTokenRepository;
use Glpi\OAuth\RefreshTokenRepository;
use Glpi\Security\Attribute\SecurityStrategy;
use Glpi\Security\SessionTracker;
use Session;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class SessionTrackerController extends AbstractController
{
    #[Route(
        path: "/Security/Session/{login_session_uid}/Revoke",
        name: "security_sessions_revoke",
        methods: ["POST"],
    )]
    #[SecurityStrategy(Firewall::STRATEGY_AUTHENTICATED)]
    public function revokeSession(Request $request): Response
    {
        global $DB;

        $login_session_uid = $request->attributes->getString('login_session_uid');
        $it = $DB->request([
            'SELECT' => ['users_id'],
            'FROM' => 'glpi_users_sessions',
            'WHERE' => ['login_session_uid' => $login_session_uid],
            'LIMIT' => 1,
        ]);
        $session = $it->current();
        $users_id = $session['users_id'] ?? null;

        if ($users_id !== Session::getLoginUserID() && !Config::canUpdate()) {
            throw new AccessDeniedHttpException();
        }
        SessionTracker::revokeSession($login_session_uid, SessionTracker::REVOKE_REASON_ADMIN);

        return new Response('', Response::HTTP_NO_CONTENT);
    }

    #[Route(
        "/Security/AccessToken/{uuid}/Revoke",
        name: "security_accesstoken_revoke",
        methods: ['POST']
    )]
    #[SecurityStrategy(Firewall::STRATEGY_AUTHENTICATED)]
    public function revokeAccessToken(Request $request): Response
    {
        $repo = new AccessTokenRepository();
        if (!\OAuthClient::canUpdate()) {
            $repo->revokeMyAccessTokenByUUID($request->attributes->getString('uuid'));
        } else {
            $repo->revokeAccessTokenByUUID($request->attributes->getString('uuid'));
        }

        return new Response('', Response::HTTP_NO_CONTENT);
    }

    #[Route(
        path: "/Security/Session/All/Revoke",
        name: "security_sessions_revokeall",
        methods: ["POST"],
        priority: 1,
    )]
    #[SecurityStrategy(Firewall::STRATEGY_AUTHENTICATED)]
    public function revokeAllSessions(Request $request): Response
    {
        $users_id = $request->request->getInt('users_id', 0);
        $is_own_sessions = $users_id > 0 && $users_id === Session::getLoginUserID();
        if (!$is_own_sessions && !\OAuthClient::canUpdate()) {
            throw new AccessDeniedHttpException();
        }

        SessionTracker::revokeAllSessionsExceptCurrent($users_id);
        $access_repo = new AccessTokenRepository();
        $refresh_repo = new RefreshTokenRepository();
        if ($users_id === 0) {
            $access_repo->revokeAll();
            $refresh_repo->revokeAll();
        } else {
            $access_repo->revokeAllForUser($users_id);
        }

        return new Response('', Response::HTTP_NO_CONTENT);
    }
}
