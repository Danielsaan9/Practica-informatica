<?php

namespace Glpi\OAuth;

use GLPIKey;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;
use OAuthClient;

use function Safe\json_decode;

class ClientRepository implements ClientRepositoryInterface
{
    /**
     * @param string $clientIdentifier
     *
     * @return ?ClientEntityInterface
     */
    public function getClientEntity($clientIdentifier): ?ClientEntityInterface
    {
        global $DB;

        $iterator = $DB->request([
            'FROM'   => 'glpi_oauthclients',
            'WHERE'  => [
                'identifier' => $clientIdentifier,
            ],
        ]);

        if (count($iterator) === 1) {
            $client = new Client();
            $client->setIdentifier($clientIdentifier);
            $client->setName($iterator->current()['name']);
            $client->setRedirectUri(json_decode($iterator->current()['redirect_uri'], true) ?? []);
            return $client;
        }

        return null;
    }

    /**
     * @param string $clientIdentifier
     * @param string $clientSecret
     * @param string $grantType
     *
     * @return bool
     * @throws OAuthServerException If the requested grant type is not allowed for the client
     */
    public function validateClient($clientIdentifier, $clientSecret, $grantType): bool
    {
        $client = new OAuthClient();
        $client->getFromDBByCrit([
            'identifier' => $clientIdentifier,
        ]);

        if ((new GLPIKey())->decrypt($client->fields['secret']) !== $clientSecret) {
            return false;
        }

        $global_grants = ['refresh_token'];
        $allowed_grants = array_merge($client->fields['grants'], $global_grants);
        if (!in_array($grantType, $allowed_grants, true)) {
            throw OAuthServerException::unauthorizedClient();
        }
        return true;
    }
}
