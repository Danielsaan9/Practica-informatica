<?php

namespace Glpi\OAuth;

use Glpi\DBAL\QueryFunction;
use Glpi\Toolbox\IPUtilities;
use League\OAuth2\Server\Entities\AuthCodeEntityInterface;
use League\OAuth2\Server\Repositories\AuthCodeRepositoryInterface;

class AuthCodeRepository implements AuthCodeRepositoryInterface
{
    public function getNewAuthCode(): AuthCode
    {
        $code = new AuthCode();
        $code->setIdentifier(bin2hex(random_bytes(Server::AUTH_CODE_LENGTH_BYTES)));
        return $code;
    }

    public function persistNewAuthCode(AuthCodeEntityInterface $authCodeEntity): void
    {
        global $DB;

        // clean expired codes
        $DB->delete('glpi_oauth_auth_codes', [
            'date_expiration' => ['<', date('Y-m-d H:i:s')],
        ]);

        $DB->insert('glpi_oauth_auth_codes', [
            'identifier' => $authCodeEntity->getIdentifier(),
            'client' => $authCodeEntity->getClient()->getIdentifier(),
            'date_expiration' => $authCodeEntity->getExpiryDateTime()->format('Y-m-d H:i:s'),
            'user_identifier' => $authCodeEntity->getUserIdentifier(),
            'scopes' => exportArrayToDB($authCodeEntity->getScopes()),
            'ip_address' => IPUtilities::getClientIP(),
        ]);
    }

    /**
     * @param string $codeId
     *
     * @return void
     */
    public function revokeAuthCode($codeId): void
    {
        global $DB;

        $DB->delete('glpi_oauth_auth_codes', ['identifier' => $codeId]);
    }

    /**
     * Revoke all authorization codes issued for the given client.
     *
     * @param string $clientIdentifier
     *
     * @return void
     */
    public function revokeByClient(string $clientIdentifier): void
    {
        global $DB;

        $DB->delete('glpi_oauth_auth_codes', ['client' => $clientIdentifier]);
    }

    /**
     * @param string $codeId
     *
     * @return bool
     */
    public function isAuthCodeRevoked($codeId): bool
    {
        global $DB;

        $iterator = $DB->request([
            'SELECT' => 'identifier',
            'FROM' => 'glpi_oauth_auth_codes',
            'WHERE' => [
                'identifier' => $codeId,
                'date_expiration' => ['>', QueryFunction::now()],
            ],
        ]);
        return $iterator->count() === 0;
    }
}
