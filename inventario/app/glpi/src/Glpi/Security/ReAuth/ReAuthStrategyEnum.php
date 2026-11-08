<?php

declare(strict_types=1);

namespace Glpi\Security\ReAuth;

enum ReAuthStrategyEnum: string
{
    case TOTP = 'totp';
    case PASSWORD = 'password';
    case LDAP = 'ldap';
    case MAIL = 'mail';
    case CAS = 'cas';
    case FALLBACK = 'fallback';

    public function createStrategy(): ReAuthStrategyInterface
    {
        return match ($this) {
            self::TOTP => new TOTPReAuthStrategy(),
            self::PASSWORD => new PasswordReAuthStrategy(),
            self::LDAP => new LdapReAuthStrategy(),
            self::MAIL => new MailReAuthStrategy(),
            self::CAS => new CasReAuthStrategy(),
            self::FALLBACK => new FallbackReAuthStrategy(),
        };
    }
}
