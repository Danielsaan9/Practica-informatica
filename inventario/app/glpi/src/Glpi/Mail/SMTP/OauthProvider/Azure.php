<?php

namespace Glpi\Mail\SMTP\OauthProvider;

final class Azure extends \TheNetworg\OAuth2\Client\Provider\Azure implements ProviderInterface
{
    public function __construct(array $options = [])
    {
        $options['scopes'] = $this->getScopes();
        $options['defaultEndPointVersion'] = self::ENDPOINT_VERSION_2_0;

        parent::__construct($options);
    }

    public function getAuthorizationUrl(array $options = [])
    {
        $options = [
            'prompt' => 'login', // ensure user will have to specify the account to use
            'scope'  => $this->getScopes(),
        ];

        return parent::getAuthorizationUrl($options);
    }

    public static function getName(): string
    {
        return 'Azure';
    }

    public static function getAdditionalParameters(): array
    {
        return [
            [
                'key'     => 'tenant',
                'label'   => _x('oauth', 'Tenant ID'),
                'default' => 'common',
                'helper'  => _x('oauth', 'Use "common" if your application is shared by multiple tenants.'),
            ],
        ];
    }

    private function getScopes(): array
    {
        return [
            'openid', // required
            'email', // required to be able to fetch login
            'offline_access',
            'https://outlook.office.com/SMTP.Send',
        ];
    }
}
