<?php

namespace Glpi\OAuth;

use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;

use function Safe\json_decode;

class ScopeRepository implements ScopeRepositoryInterface
{
    public function getScopeEntityByIdentifier($identifier): ?ScopeEntityInterface
    {
        $scope = new Scope();
        $scope->setIdentifier($identifier);
        return $scope;
    }

    /**
     * @param array $scopes
     * @param string $grantType
     * @param ClientEntityInterface $clientEntity
     * @param ?string $userIdentifier
     * @param ?string $authCodeId
     *
     * @return ScopeEntityInterface[]
     */
    public function finalizeScopes(array $scopes, $grantType, ClientEntityInterface $clientEntity, $userIdentifier = null, ?string $authCodeId = null): array
    {
        global $DB;

        $allowed_scopes = json_decode($DB->request([
            'SELECT' => 'scopes',
            'FROM'   => 'glpi_oauthclients',
            'WHERE'  => [
                'identifier' => $clientEntity->getIdentifier(),
            ],
        ])->current()['scopes']);
        if (!is_array($allowed_scopes)) {
            $allowed_scopes = [];
        }
        return array_filter($scopes, static fn($scope) => in_array($scope->getIdentifier(), $allowed_scopes, true));
    }
}
