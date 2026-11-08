<?php

namespace Glpi\Mail\SMTP\OauthProvider;

final class Google extends \League\OAuth2\Client\Provider\Google implements ProviderInterface
{
    public function __construct(array $options = [])
    {
        $options['scopes'] = $this->getScopes();
        $options['accessType'] = 'offline';

        parent::__construct($options);
    }

    public function getAuthorizationUrl(array $options = [])
    {
        $options = [
            'prompt' => 'consent select_account', // ensure user will have to specify the account to use
            'scope'  => $this->getScopes(),
        ];

        return parent::getAuthorizationUrl($options);
    }

    public static function getName(): string
    {
        return 'Google';
    }

    public static function getAdditionalParameters(): array
    {
        return [
        ];
    }

    private function getScopes(): array
    {
        return [
            'https://mail.google.com/',
        ];
    }
}
