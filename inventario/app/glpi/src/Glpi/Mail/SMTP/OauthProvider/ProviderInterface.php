<?php

namespace Glpi\Mail\SMTP\OauthProvider;

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Token\AccessTokenInterface;

interface ProviderInterface
{
    public function __construct(array $options = []);

    /**
     * @return string
     * @see AbstractProvider::getAuthorizationUrl()
     */
    public function getAuthorizationUrl(array $options = []);

    /**
     * @param string $grant
     * @param array $options
     *
     * @return AccessTokenInterface
     * @see AbstractProvider::getAccessToken()
     */
    public function getAccessToken($grant, array $options = []);

    /**
     * Requests and returns the resource owner of given access token.
     *
     * @param  AccessToken $token
     * @return ResourceOwnerInterface
     * @see AbstractProvider::getResourceOwner()
     */
    public function getResourceOwner(AccessToken $token);

    /**
     * Returns provider name.
     *
     * @return string
     */
    public static function getName(): string;

    /**
     * Returns additional parameters.
     * Result is an array of parameters, each one is an array having following values:
     *  - `key` (mandatory): key to use when passing the value on instance constructor options
     *  - `label` (mandatory): label to display on configuration form
     *  - `default` (optional): default value
     *  - `helper` (optional): text displayed in helper tooltip
     *
     * @return array
     */
    public static function getAdditionalParameters(): array;
}
