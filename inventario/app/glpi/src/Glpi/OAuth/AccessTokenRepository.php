<?php

namespace Glpi\OAuth;

use Glpi\DBAL\QueryExpression;
use Glpi\Toolbox\IPUtilities;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use Ramsey\Uuid\Uuid;
use Safe\DateTime;
use Session;

class AccessTokenRepository implements AccessTokenRepositoryInterface
{
    /**
     * @param ClientEntityInterface $clientEntity
     * @param array $scopes
     * @param ?string $userIdentifier
     *
     * @return AccessTokenEntityInterface
     */
    public function getNewToken(ClientEntityInterface $clientEntity, array $scopes, $userIdentifier = null): AccessTokenEntityInterface
    {
        $token = new AccessToken();
        $token->setClient($clientEntity);
        if ($userIdentifier !== null) {
            $token->setUserIdentifier($userIdentifier);
        }
        foreach ($scopes as $scope) {
            $token->addScope($scope);
        }
        return $token;
    }

    public function persistNewAccessToken(AccessTokenEntityInterface $accessTokenEntity): void
    {
        global $DB;

        // clean expired tokens
        $DB->delete('glpi_oauth_access_tokens', [
            'date_expiration' => ['<', date('Y-m-d H:i:s')],
        ]);

        $DB->insert('glpi_oauth_access_tokens', [
            'identifier' => $accessTokenEntity->getIdentifier(),
            'client' => $accessTokenEntity->getClient()->getIdentifier(),
            'date_expiration' => $accessTokenEntity->getExpiryDateTime()->format('Y-m-d H:i:s'),
            'user_identifier' => $accessTokenEntity->getUserIdentifier(),
            'scopes' => exportArrayToDB($accessTokenEntity->getScopes()),
            'ip_address' => IPUtilities::getClientIP(),
            'user_agent' => isset($_SERVER['HTTP_USER_AGENT']) ? substr($_SERVER['HTTP_USER_AGENT'], 0, 512) : null,
            'uuid' => Uuid::uuid4()->toString(),
        ]);
    }

    /**
     * @param string $tokenId
     *
     * @return void
     */
    public function revokeAccessToken($tokenId): void
    {
        global $DB;
        $DB->delete('glpi_oauth_access_tokens', ['identifier' => $tokenId]);
    }

    /**
     * Revoke all access tokens issued for the given client.
     *
     * @param string $clientIdentifier
     *
     * @return void
     */
    public function revokeByClient(string $clientIdentifier): void
    {
        global $DB;

        $DB->delete('glpi_oauth_access_tokens', ['client' => $clientIdentifier]);
    }

    /**
     * Revoke access token by its UUID.
     * Useful in cases where the token may be revoked by the frontend where it is not desirable to expose the real access token.
     * @param string $uuid
     * @return void
     */
    public function revokeAccessTokenByUUID(string $uuid): void
    {
        global $DB;
        $DB->delete('glpi_oauth_access_tokens', ['uuid' => $uuid]);
    }

    /**
     * Revoke access token by its UUID if it belongs to the current user.
     * @param string $uuid
     * @return void
     * @see AccessTokenRepository::revokeAccessTokenByUUID()
     */
    public function revokeMyAccessTokenByUUID(string $uuid): void
    {
        global $DB;
        $DB->delete('glpi_oauth_access_tokens', [
            'uuid' => $uuid,
            'user_identifier' => Session::getLoginUserID(),
        ]);
    }

    /**
     * Revoke all access tokens.
     *
     * @return void
     */
    public function revokeAll(): void
    {
        global $DB;
        $DB->delete('glpi_oauth_access_tokens', [new QueryExpression('true')]);
    }

    /**
     * Revoke all access tokens for a specific user.
     *
     * @param int $users_id
     *
     * @return void
     */
    public function revokeAllForUser(int $users_id): void
    {
        global $DB;
        $DB->delete('glpi_oauth_access_tokens', ['user_identifier' => $users_id]);
    }

    /**
     * @param string $tokenId
     *
     * @return bool
     */
    public function isAccessTokenRevoked($tokenId): bool
    {
        global $DB;

        $iterator = $DB->request([
            'SELECT' => ['identifier', 'date_expiration'],
            'FROM' => 'glpi_oauth_access_tokens',
            'WHERE' => [
                'identifier' => $tokenId,
            ],
        ]);
        if (count($iterator) === 0) {
            return true;
        }
        // Check if the token is expired
        $expiration = $iterator->current()['date_expiration'];
        return (new DateTime($expiration)) < new DateTime();
    }
}
