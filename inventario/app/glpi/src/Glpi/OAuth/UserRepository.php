<?php

namespace Glpi\OAuth;

use Auth;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\UserEntityInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    /**
     * @param string $username
     * @param string $password
     * @param string $grantType
     * @param ClientEntityInterface $clientEntity
     *
     * @return ?UserEntityInterface
     */
    public function getUserEntityByUserCredentials($username, $password, $grantType, ClientEntityInterface $clientEntity): ?UserEntityInterface
    {
        $auth = new Auth();
        $valid_login = $auth->validateLogin($username, $password, true);

        if (!$valid_login || !$auth->applyValidatedLogin() || empty($auth->user->fields['id'])) {
            return null;
        }

        $user_entity = new User();
        $user_entity->setIdentifier($auth->user->fields['id']);
        return $user_entity;
    }
}
