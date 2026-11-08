<?php

namespace Glpi\Application\View\Extension;

use GLPIKey;
use Session;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

/**
 * @since 10.0.0
 */
class SecurityExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('csrf_token', [Session::class, 'getNewCSRFToken']), // TODO: delete in GLPI 13
            new TwigFunction('idor_token', [Session::class, 'getNewIDORToken']),
        ];
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('decrypt', [$this, 'decrypt']),
        ];
    }

    /**
     * @param string|null $value
     * @return string
     */
    public function decrypt($value): string
    {
        if ($value === null) {
            return '';
        }

        return (new GLPIKey())->decrypt($value);
    }
}
