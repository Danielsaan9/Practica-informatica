<?php

namespace Glpi\OAuth;

use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\Traits\ClientTrait;
use League\OAuth2\Server\Entities\Traits\EntityTrait;

class Client implements ClientEntityInterface
{
    use ClientTrait;
    use EntityTrait;

    public function __construct()
    {
        $this->setRedirectUri([]);
        $this->isConfidential = true;
    }

    /**
     * @param string $name
     *
     * @return void
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @param array $redirectUri
     */
    public function setRedirectUri(array $redirectUri): void
    {
        $global_allowed_redirect_uri = [
            '/api.php/oauth2/redirection', // No effect. Maybe don't need it.
            '/api.php/swagger-oauth-redirect', // Used for Swagger UI
        ];
        $this->redirectUri = array_merge($global_allowed_redirect_uri, $redirectUri);
    }
}
