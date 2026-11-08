<?php

namespace Glpi\OAuth;

use Glpi\DBAL\QueryExpression;
use Glpi\DBAL\QuerySubQuery;
use Glpi\Toolbox\IPUtilities;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;

class RefreshTokenRepository implements RefreshTokenRepositoryInterface
{
    /**
     * @return RefreshToken|null
     */
    public function getNewRefreshToken(): ?RefreshTokenEntityInterface
    {
        return new RefreshToken();
    }

    public function persistNewRefreshToken(RefreshTokenEntityInterface $refreshTokenEntity): void
    {
        global $DB;

        // clean expired tokens
        $DB->delete('glpi_oauth_refresh_tokens', [
            'date_expiration' => ['<', date('Y-m-d H:i:s')],
        ]);

        $DB->insert('glpi_oauth_refresh_tokens', [
            'identifier' => $refreshTokenEntity->getIdentifier(),
            'access_token' => $refreshTokenEntity->getAccessToken()->getIdentifier(),
            'date_expiration' => $refreshTokenEntity->getExpiryDateTime()->format('Y-m-d H:i:s'),
            'ip_address' => IPUtilities::getClientIP(),
        ]);
    }

    /**
     * @param string $tokenId
     *
     * @return void
     */
    public function revokeRefreshToken($tokenId): void
    {
        global $DB;

        $DB->delete('glpi_oauth_refresh_tokens', [
            'identifier' => $tokenId,
        ]);
    }

    /**
     * Revoke all refresh tokens whose linked access token was issued for the given client.
     *
     * @param string $clientIdentifier
     *
     * @return void
     */
    public function revokeByClient(string $clientIdentifier): void
    {
        global $DB;

        $DB->delete('glpi_oauth_refresh_tokens', [
            'access_token' => new QuerySubQuery([
                'SELECT' => 'identifier',
                'FROM' => 'glpi_oauth_access_tokens',
                'WHERE' => ['client' => $clientIdentifier],
            ]),
        ]);
    }

    /**
     * Revoke all refresh tokens.
     *
     * @return void
     */
    public function revokeAll(): void
    {
        global $DB;
        $DB->delete('glpi_oauth_refresh_tokens', [new QueryExpression('true')]);
    }

    /**
     * @param string $tokenId
     *
     * @return bool
     */
    public function isRefreshTokenRevoked($tokenId): bool
    {
        global $DB;

        $iterator = $DB->request([
            'SELECT' => 'identifier',
            'FROM' => 'glpi_oauth_refresh_tokens',
            'WHERE' => [
                'identifier' => $tokenId,
            ],
        ]);

        return $iterator->count() === 0;
    }
}
